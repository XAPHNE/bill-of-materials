<?php

use App\Enums\BomClassification;
use App\Enums\BomLevel;
use App\Enums\BomStatus;
use App\Enums\BomType;
use App\Exports\BomXlsxExporter;
use App\Models\Bom;
use App\Models\HbomComponent;
use App\Models\Product;
use App\Models\SbomComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function bomForExport(BomType $type, ?BomLevel $level = null, ?BomClassification $classification = null): Bom
{
    $product = Product::query()->create([
        'name' => 'Export test product',
        'vendor' => 'Acme',
        'version' => '1.0',
        'category' => 'software',
    ]);

    return Bom::query()->create([
        'product_id' => $product->id,
        'type' => $type,
        'level' => $level,
        'classification' => $classification,
        'name' => 'Export test '.$type->value,
        'version' => '1.0',
        'status' => BomStatus::Draft,
    ]);
}

/**
 * @return array{0: string, 1: string}
 */
function exportToTempFile(Bom $bom): array
{
    $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'bom-test-'.uniqid().'.xlsx';

    (new BomXlsxExporter)->export($bom, $path);

    $workbookXml = '';

    if (class_exists(ZipArchive::class)) {
        $zip = new ZipArchive;
        $zip->open($path);
        $workbookXml = (string) $zip->getFromName('xl/workbook.xml');
        $zip->close();
    }

    return [$path, $workbookXml];
}

it('exports an SBOM with the CERT-In minimum element headers', function () {
    $bom = bomForExport(BomType::Sbom, BomLevel::Complete, BomClassification::Build);

    SbomComponent::query()->create([
        'bom_id' => $bom->id,
        'name' => 'laravel/framework',
        'version' => '13.40.0',
        'description' => 'PHP framework',
        'supplier' => 'Laravel LLC',
        'component_type' => 'framework',
        'scope' => 'required',
        'license' => 'MIT',
        'origin' => 'open-source',
        'dependencies' => 'php',
        'patch_status' => 'patched',
        'release_date' => '2026-05-14',
        'end_of_life' => '2027-05-14',
        'criticality' => 'medium',
        'author' => 'Acme Security',
        'is_executable' => true,
        'purl' => 'pkg:composer/laravel/framework@13.40.0',
        'vulnerabilities_count' => 0,
    ]);

    [$path, $workbookXml] = exportToTempFile($bom);

    expect(file_get_contents($path, length: 2))->toBe('PK')
        ->and(filesize($path))->toBeGreaterThan(0)
        ->and($workbookXml)->toContain('BOM Details')
        ->and($workbookXml)->toContain('SBOM');

    @unlink($path);
});

it('exports every other BOM type with a matching sheet', function (BomType $type, string $sheet) {
    $bom = bomForExport($type);

    [$path, $workbookXml] = exportToTempFile($bom);

    expect(file_get_contents($path, length: 2))->toBe('PK')
        ->and($workbookXml)->toContain($sheet);

    @unlink($path);
})->with([
    'QBOM' => [BomType::Qbom, 'QBOM'],
    'CBOM' => [BomType::Cbom, 'CBOM'],
    'AIBOM' => [BomType::Aibom, 'AIBOM'],
    'HBOM' => [BomType::Hbom, 'HBOM'],
]);

it('exports HBOM sub-components recursively', function () {
    $bom = bomForExport(BomType::Hbom);

    $parent = HbomComponent::query()->create([
        'bom_id' => $bom->id,
        'name' => 'Edge Gateway X1',
        'manufacturer' => 'Acme Networks',
        'category' => 'other',
        'quantity' => 1,
        'criticality' => 'high',
    ]);

    HbomComponent::query()->create([
        'bom_id' => $bom->id,
        'parent_id' => $parent->id,
        'name' => 'NXP i.MX 8M Plus',
        'manufacturer' => 'NXP',
        'category' => 'processor',
        'quantity' => 1,
        'criticality' => 'critical',
    ]);

    expect($bom->entryCount())->toBe(2);

    [$path, $workbookXml] = exportToTempFile($bom->refresh());

    expect($workbookXml)->toContain('HBOM');

    @unlink($path);
});

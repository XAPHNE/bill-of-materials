<?php

namespace App\Exports;

use App\Enums\BomType;
use App\Models\Bom;
use App\Models\HbomComponent;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

/**
 * Exports a Bill of Materials to a multi-sheet XLSX workbook.
 *
 * The first sheet holds the BOM header information and every following sheet
 * holds one row per entry using the CERT-In minimum element names (Tables 5,
 * 8/9, 10 and 11 of the version 2.0 guidelines) as column headers.
 */
class BomXlsxExporter
{
    public function export(Bom $bom, string $path): string
    {
        $writer = new Writer;
        $writer->openToFile($path);

        $this->writeDetailsSheet($writer, $bom);
        $this->writeEntriesSheet($writer, $bom);

        $writer->close();

        return $path;
    }

    public function suggestedFileName(Bom $bom): string
    {
        $slug = str($bom->name)->slug()->limit(60, '')->toString();

        return sprintf('%s-%s-%s.xlsx', $bom->type->value, $slug ?: 'bom', $bom->version);
    }

    private function writeDetailsSheet(Writer $writer, Bom $bom): void
    {
        $bom->loadMissing('product', 'creator');

        $sheet = $writer->getCurrentSheet();
        $sheet->setName('BOM Details');
        $sheet->setColumnWidth(24, 1);
        $sheet->setColumnWidth(70, 2);

        $this->addRow($writer, ['Field', 'Value']);

        foreach ($this->details($bom) as $label => $value) {
            $this->addRow($writer, [$label, $value]);
        }

        foreach ($bom->metadata ?? [] as $key => $value) {
            $this->addRow($writer, ['Metadata: '.$key, $this->stringify($value)]);
        }
    }

    /**
     * @return array<string, string|int|null>
     */
    private function details(Bom $bom): array
    {
        return [
            'BOM Name' => $bom->name,
            'BOM Type' => $bom->type->getLabel(),
            'BOM Version' => $bom->version,
            'SBOM Level' => $bom->level?->getLabel(),
            'SBOM Classification' => $bom->classification?->getLabel(),
            'Specification' => $bom->specification,
            'Status' => $bom->status->getLabel(),
            'Product' => $bom->product?->name,
            'Product Vendor' => $bom->product?->vendor,
            'Product Version' => $bom->product?->version,
            'Product Category' => $bom->product?->category,
            'Description' => $bom->description,
            'Author' => $bom->creator?->name,
            'Released At' => $bom->released_at?->format('Y-m-d'),
            'Created At' => $bom->created_at?->format('Y-m-d H:i'),
            'Updated At' => $bom->updated_at?->format('Y-m-d H:i'),
            'Total Entries' => $bom->entryCount(),
        ];
    }

    private function writeEntriesSheet(Writer $writer, Bom $bom): void
    {
        [$name, $headers, $rows] = match ($bom->type) {
            BomType::Sbom => ['SBOM', $this->sbomHeaders(), $this->sbomRows($bom)],
            BomType::Qbom => ['QBOM', $this->qbomHeaders(), $this->qbomRows($bom)],
            BomType::Cbom => ['CBOM', $this->cbomHeaders(), $this->cbomRows($bom)],
            BomType::Aibom => ['AIBOM', $this->aibomHeaders(), $this->aibomRows($bom)],
            BomType::Hbom => ['HBOM', $this->hbomHeaders(), $this->hbomRows($bom)],
        };

        $writer->addNewSheetAndMakeItCurrent()->setName($name);

        $this->addRow($writer, $headers);

        foreach ($rows as $row) {
            $this->addRow($writer, $row);
        }
    }

    /**
     * @return array<int, string>
     */
    private function sbomHeaders(): array
    {
        return [
            'Component Name', 'Component Version', 'Component Description', 'Component Supplier',
            'Component License', 'Component Origin', 'Component Dependencies', 'Vulnerabilities',
            'Patch Status', 'Release Date', 'End-of-Life (EOL) Date', 'Criticality',
            'Usage Restrictions', 'Checksums or Hashes', 'Comments or Notes', 'Author of SBOM Data',
            'Timestamp', 'Executable Property', 'Archive Property', 'Structured Property', 'Unique Identifier',
        ];
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    private function sbomRows(Bom $bom): array
    {
        return $bom->sbomComponents()->orderBy('name')->get()->map(fn ($component): array => [
            $component->name,
            $component->version,
            $component->description,
            $component->supplier,
            $component->license,
            $component->origin,
            $component->dependencies,
            $this->vulnerabilitySummary($component->vulnerabilities, $component->vulnerabilities_count),
            $component->patch_status,
            $component->release_date?->format('Y-m-d'),
            $component->end_of_life?->format('Y-m-d'),
            $component->criticality,
            $component->usage_restrictions,
            $component->hash_algorithm ? $component->hash_algorithm.': '.$component->hash_value : $component->hash_value,
            $component->notes,
            $component->author,
            $component->updated_at?->format('Y-m-d H:i'),
            $this->yesNo($component->is_executable),
            $this->yesNo($component->is_archive),
            $this->yesNo($component->is_structured),
            $component->purl ?: $component->cpe,
        ])->all();
    }

    /**
     * @return array<int, string>
     */
    private function qbomHeaders(): array
    {
        return [
            'Model Name', 'Version', 'Vendor', 'Origin', 'License Information', 'Cryptographic Asset Type',
            'Current Algorithm', 'Primitive', 'Mode', 'Crypto Functions', 'Classical Security Level', 'OID',
            'Algorithms Employed', 'Key ID', 'Key State', 'Key Size', 'Key Creation Date', 'Key Activation Date',
            'Protocol Version', 'Cipher Suites', 'Subject Name', 'Issuer Name', 'Not Valid Before',
            'Not Valid After', 'Signature Algorithm', 'Public Key Reference', 'Certificate Format',
            'Certificate Extension', 'Quantum Risk', 'Migration Status', 'Target Algorithm',
            'Communication Protocol', 'Hardware', 'Software Dependencies', 'Environmental Impact',
            'Vulnerabilities', 'Attestations', 'Comments or Notes',
        ];
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    private function qbomRows(Bom $bom): array
    {
        return $bom->qbomAssets()->orderBy('name')->get()->map(fn ($asset): array => [
            $asset->name,
            $asset->version,
            $asset->vendor,
            $asset->origin,
            $asset->license,
            $asset->asset_type,
            $asset->current_algorithm,
            $asset->primitive,
            $asset->mode,
            $asset->crypto_functions,
            $asset->classical_security_level,
            $asset->oid,
            $asset->algorithm_list,
            $asset->key_identifier,
            $asset->key_state,
            $asset->key_size,
            $asset->key_created_at?->format('Y-m-d'),
            $asset->key_activated_at?->format('Y-m-d'),
            $asset->protocol_version,
            $asset->cipher_suites,
            $asset->subject_name,
            $asset->issuer_name,
            $asset->not_valid_before?->format('Y-m-d'),
            $asset->not_valid_after?->format('Y-m-d'),
            $asset->signature_algorithm,
            $asset->public_key_reference,
            $asset->certificate_format,
            $asset->certificate_extension,
            $asset->quantum_risk,
            $asset->migration_status,
            $asset->target_algorithm,
            $asset->communication_protocol,
            $asset->hardware,
            $asset->software_dependencies,
            $asset->environmental_impact,
            $asset->vulnerabilities,
            $asset->attestations,
            $asset->notes,
        ])->all();
    }

    /**
     * @return array<int, string>
     */
    private function cbomHeaders(): array
    {
        return [
            'Name', 'Asset Type', 'Algorithm', 'Primitive', 'Mode', 'Crypto Functions',
            'Classical Security Level', 'OID', 'Key ID', 'Key State', 'Key Size',
            'Key Creation Date', 'Key Activation Date', 'Protocol Version', 'Cipher Suites',
            'Subject Name', 'Issuer Name', 'Not Valid Before', 'Not Valid After',
            'Signature Algorithm', 'Public Key Reference', 'Certificate Format', 'Certificate Extension',
            'Provider', 'Location', 'Quantum Safe', 'Comments or Notes',
        ];
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    private function cbomRows(Bom $bom): array
    {
        return $bom->cbomAssets()->orderBy('name')->get()->map(fn ($asset): array => [
            $asset->name,
            $asset->asset_type,
            $asset->algorithm,
            $asset->primitive,
            $asset->mode,
            $asset->crypto_functions,
            $asset->classical_security_level,
            $asset->oid,
            $asset->key_identifier,
            $asset->key_state,
            $asset->key_size,
            $asset->key_created_at?->format('Y-m-d'),
            $asset->key_activated_at?->format('Y-m-d'),
            $asset->protocol_version,
            $asset->cipher_suites,
            $asset->subject_name,
            $asset->issuer_name,
            $asset->not_valid_before?->format('Y-m-d'),
            $asset->not_valid_after?->format('Y-m-d'),
            $asset->signature_algorithm,
            $asset->public_key_reference,
            $asset->certificate_format,
            $asset->certificate_extension,
            $asset->provider,
            $asset->location,
            $this->yesNo($asset->quantum_safe),
            $asset->notes,
        ])->all();
    }

    /**
     * @return array<int, string>
     */
    private function aibomHeaders(): array
    {
        return [
            'Model Name', 'Model Version', 'Model Type', 'Model Developer', 'Model Licensing Information',
            'Software Dependencies', 'ML Models and Algorithms', 'Model Performance Metrics', 'Data Source',
            'Data Sets', 'Hardware', 'Security Requirements', 'Input', 'Output', 'Intended Usage',
            'Out of Scope Usage', 'Environmental Impact', 'Vulnerabilities', 'Attestations',
            'Risk Classification', 'Evaluation', 'Comments or Notes',
        ];
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    private function aibomRows(Bom $bom): array
    {
        return $bom->aibomAssets()->orderBy('name')->get()->map(fn ($asset): array => [
            $asset->name,
            $asset->version,
            $asset->model_type,
            $asset->provider,
            $asset->license,
            $asset->software_dependencies,
            $asset->algorithms,
            $asset->performance_metrics,
            $asset->data_source,
            $asset->dataset,
            $asset->hardware,
            $asset->security_requirements,
            $asset->model_input,
            $asset->model_output,
            $asset->purpose,
            $asset->out_of_scope_usage,
            $asset->environmental_impact,
            $asset->vulnerabilities,
            $asset->attestations,
            $asset->risk_classification,
            $asset->evaluation,
            $asset->notes,
        ])->all();
    }

    /**
     * @return array<int, string>
     */
    private function hbomHeaders(): array
    {
        return [
            'Product Name', 'Product Version', 'Product Details', 'Warranty / AMC',
            'Manufacturer Name', 'Manufacturer Location', 'Manufacturing Date', 'Supplier Information',
            'Supplier Location', 'Model Number', 'Serial Number', 'Technical Specification',
            'Technology Node', 'Compliance', 'Power Supply', 'License Information', 'Test Result',
            'Sub-component Of', 'Category', 'Quantity', 'Firmware Version', 'Country of Origin',
            'Criticality', 'End-of-Life Date', 'Vulnerabilities', 'Comments or Notes',
        ];
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    private function hbomRows(Bom $bom): array
    {
        $rows = [];

        $components = $bom->hbomComponents()->with('children')->orderBy('name')->get();

        $flatten = function (HbomComponent $component, ?string $parent, int $depth) use (&$flatten, &$rows): void {
            $rows[] = [
                str_repeat('— ', $depth).$component->name,
                $component->product_version,
                $component->product_details,
                $component->warranty,
                $component->manufacturer,
                $component->manufacturer_location,
                $component->manufacturing_date?->format('Y-m-d'),
                $component->supplier_name,
                $component->supplier_location,
                $component->model_number,
                $component->serial_number,
                $component->technical_specification,
                $component->technology_node,
                $component->compliance,
                $component->power_supply,
                $component->license,
                $component->test_result,
                $parent,
                $component->category,
                $component->quantity,
                $component->firmware_version,
                $component->country_of_origin,
                $component->criticality,
                $component->end_of_life?->format('Y-m-d'),
                $component->vulnerabilities,
                $component->notes,
            ];

            foreach ($component->children->sortBy('name') as $child) {
                $flatten($child, $component->name, $depth + 1);
            }
        };

        foreach ($components as $component) {
            $flatten($component, null, 0);
        }

        return $rows;
    }

    /**
     * @param  array<int, mixed>  $values
     */
    private function addRow(Writer $writer, array $values): void
    {
        $writer->addRow(Row::fromValues(
            array_map(fn ($value): string|int|float|bool|null => $this->cellValue($value), $values),
        ));
    }

    private function cellValue(mixed $value): string|int|float|bool|null
    {
        return match (true) {
            $value === null => null,
            is_bool($value), is_int($value), is_float($value) => $value,
            default => $this->stringify($value),
        };
    }

    private function stringify(mixed $value): string
    {
        return match (true) {
            is_array($value) => json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            is_bool($value) => $value ? 'Yes' : 'No',
            default => (string) $value,
        };
    }

    private function yesNo(?bool $value): string
    {
        return $value ? 'Yes' : 'No';
    }

    private function vulnerabilitySummary(?string $references, ?int $count): ?string
    {
        $summary = trim((string) $references);

        if ($count) {
            $summary = trim($summary.' ('.$count.' known)');
        }

        return $summary !== '' ? $summary : null;
    }
}

<?php

namespace Database\Seeders;

use App\Enums\BomClassification;
use App\Enums\BomLevel;
use App\Enums\BomStatus;
use App\Enums\BomType;
use App\Models\AibomAsset;
use App\Models\Bom;
use App\Models\CbomAsset;
use App\Models\HbomComponent;
use App\Models\Product;
use App\Models\QbomAsset;
use App\Models\SbomComponent;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoBomSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();

        $payments = Product::query()->create([
            'name' => 'Payments API',
            'vendor' => 'Acme Digital',
            'version' => '4.2.0',
            'category' => 'software',
            'description' => 'Customer-facing payment processing API and its dependencies.',
        ]);

        $gateway = Product::query()->create([
            'name' => 'Edge Gateway X1',
            'vendor' => 'Acme Networks',
            'version' => '2.1',
            'category' => 'hardware',
            'description' => 'Branch office edge gateway with embedded firmware.',
        ]);

        $copilot = Product::query()->create([
            'name' => 'Support Copilot',
            'vendor' => 'Acme Digital',
            'version' => '1.0.0',
            'category' => 'ai-system',
            'description' => 'Retrieval-augmented assistant used by the customer support team.',
        ]);

        $paymentsSbom = $this->bom($payments, BomType::Sbom, 'Payments API — software', $user, BomStatus::Approved, '2026-09-12', [
            'standard' => 'CycloneDX',
            'standard_version' => '1.6',
        ], BomLevel::Complete, BomClassification::Build);

        foreach ([
            [
                'laravel/framework', '13.40.0', 'Laravel LLC', 'framework', 'MIT',
                'pkg:composer/laravel/framework@13.40.0', 0, 'open-source', 'PHP web application framework',
                'php, symfony/*', 'patched', '2026-05-14', '2027-05-14', 'medium',
                'None', true, false, true,
            ],
            [
                'guzzlehttp/guzzle', '7.9.2', 'Guzzle', 'library', 'MIT',
                'pkg:composer/guzzlehttp/guzzle@7.9.2', 1, 'third-party vendor', 'HTTP client library',
                'psr/http-message, guzzlehttp/promises', 'update_available', '2025-06-20', '2026-12-31', 'high',
                'Upgrade to 7.9.3 before 2026-12-31', true, false, true,
            ],
            [
                'openssl', '3.3.1', 'OpenSSL Project', 'library', 'Apache-2.0',
                'pkg:generic/openssl@3.3.1', 2, 'open-source', 'TLS and cryptography toolkit',
                'glibc', 'unpatched', '2025-01-22', '2027-04-11', 'critical',
                'Internal use only; export restrictions (EAR) apply', true, false, false,
            ],
            [
                'nginx', '1.27.0', 'F5', 'application', 'BSD-2-Clause',
                'pkg:generic/nginx@1.27.0', 0, 'third-party vendor', 'Reverse proxy and web server',
                'openssl, pcre', 'patched', '2025-05-28', '2026-11-30', 'high',
                'None', true, true, false,
            ],
            [
                'eclipse-temurin', '21', 'Eclipse Foundation', 'container', 'GPL-2.0',
                'pkg:docker/eclipse-temurin@21', 0, 'open-source', 'Java runtime container image',
                'glibc', 'patched', '2026-03-19', '2027-03-19', 'medium',
                'None', true, true, false,
            ],
            [
                'glibc', '2.39', 'GNU', 'operating-system', 'LGPL-2.1',
                'pkg:generic/glibc@2.39', 0, 'open-source', 'GNU C library',
                'linux-kernel', 'patched', '2026-01-31', '2029-01-31', 'high',
                'None', false, false, false,
            ],
        ] as [
            $name, $version, $supplier, $type, $license, $purl, $vulnerabilities, $origin,
            $description, $dependencies, $patchStatus, $releaseDate, $endOfLife, $criticality,
            $restrictions, $executable, $archive, $structured,
        ]) {
            SbomComponent::query()->create([
                'bom_id' => $paymentsSbom->id,
                'name' => $name,
                'version' => $version,
                'description' => $description,
                'supplier' => $supplier,
                'component_type' => $type,
                'scope' => 'required',
                'license' => $license,
                'origin' => $origin,
                'dependencies' => $dependencies,
                'vulnerabilities' => $vulnerabilities > 0
                    ? $vulnerabilities.' CVE reference(s) tracked in the vulnerability register.'
                    : null,
                'patch_status' => $patchStatus,
                'release_date' => $releaseDate,
                'end_of_life' => $endOfLife,
                'criticality' => $criticality,
                'usage_restrictions' => $restrictions,
                'author' => 'Acme Digital Product Security',
                'is_executable' => $executable,
                'is_archive' => $archive,
                'is_structured' => $structured,
                'purl' => $purl,
                'hash_algorithm' => 'SHA-256',
                'hash_value' => hash('sha256', $name.$version),
                'vulnerabilities_count' => $vulnerabilities,
                'notes' => 'Seeded demo component.',
            ]);
        }

        $paymentsCbom = $this->bom($payments, BomType::Cbom, 'Payments API — cryptography', $user, BomStatus::InReview, '2026-10-01', [
            'standard' => 'CycloneDX',
            'standard_version' => '1.6',
        ]);

        foreach ([
            [
                'name' => 'TLS certificate',
                'asset_type' => 'certificate',
                'algorithm' => 'ECDSA P-256',
                'key_size' => 256,
                'primitive' => 'signature',
                'quantum_safe' => false,
                'provider' => 'Let\'s Encrypt',
                'location' => 'load balancer',
                'protocol_version' => 'TLS 1.1',
                'subject_name' => 'CN=api.acme.example',
                'issuer_name' => 'CN=R3, O=Let\'s Encrypt',
                'not_valid_before' => '2026-03-01',
                'not_valid_after' => '2026-05-30',
                'signature_algorithm' => 'SHA256withECDSA',
                'public_key_reference' => 'pubkey-ecdsa-p256',
                'certificate_format' => 'X.509',
                'certificate_extension' => '.crt',
            ],
            [
                'name' => 'Session encryption',
                'asset_type' => 'algorithm',
                'algorithm' => 'AES-256-GCM',
                'key_size' => 256,
                'primitive' => 'encryption',
                'quantum_safe' => true,
                'provider' => 'OpenSSL 3.3',
                'location' => 'src/Support/Crypto.php',
                'mode' => 'gcm',
                'crypto_functions' => 'key generation, encryption, decryption, authentication',
                'classical_security_level' => '256 bits',
                'oid' => '2.16.840.1.101.3.4.1.46',
            ],
            [
                'name' => 'API key hashing',
                'asset_type' => 'algorithm',
                'algorithm' => 'SHA-256',
                'key_size' => 256,
                'primitive' => 'hash',
                'quantum_safe' => true,
                'provider' => 'PHP sodium',
                'location' => 'src/Auth/ApiKey.php',
                'crypto_functions' => 'hashing, verification',
                'classical_security_level' => '128 bits',
                'oid' => '2.16.840.1.101.3.4.2.1',
            ],
            [
                'name' => 'Key agreement',
                'asset_type' => 'protocol',
                'algorithm' => 'X25519',
                'key_size' => 255,
                'primitive' => 'key_exchange',
                'quantum_safe' => false,
                'provider' => 'OpenSSL 3.3',
                'location' => 'src/Http/Handshake.php',
                'crypto_functions' => 'key exchange',
                'classical_security_level' => '128 bits',
                'protocol_version' => 'TLS 1.3',
                'cipher_suites' => 'TLS_AES_256_GCM_SHA384, TLS_CHACHA20_POLY1305_SHA256',
            ],
        ] as $asset) {
            CbomAsset::query()->create([
                'bom_id' => $paymentsCbom->id,
                ...$asset,
            ]);
        }

        $paymentsQbom = $this->bom($payments, BomType::Qbom, 'Payments API — quantum readiness', $user, BomStatus::Draft, null, [
            'standard' => 'CERT-In QBOM',
        ]);

        foreach ([
            [
                'name' => 'TLS certificate',
                'asset_type' => 'certificate',
                'current_algorithm' => 'ECDSA P-256',
                'quantum_risk' => 'critical',
                'migration_status' => 'in_progress',
                'target_algorithm' => 'ML-DSA-65 (FIPS 204)',
                'primitive' => 'signature',
                'key_size' => 256,
                'subject_name' => 'CN=api.acme.example',
                'issuer_name' => 'CN=R3, O=Let\'s Encrypt',
                'not_valid_before' => '2026-03-01',
                'not_valid_after' => '2026-05-30',
                'signature_algorithm' => 'SHA256withECDSA',
                'certificate_format' => 'X.509',
                'certificate_extension' => '.crt',
            ],
            [
                'name' => 'Session encryption',
                'asset_type' => 'algorithm',
                'current_algorithm' => 'AES-256-GCM',
                'quantum_risk' => 'low',
                'migration_status' => 'completed',
                'target_algorithm' => 'AES-256-GCM (Grover resistant)',
                'primitive' => 'encryption',
                'mode' => 'gcm',
                'crypto_functions' => 'key generation, encryption, decryption, authentication',
                'classical_security_level' => '256 bits',
                'oid' => '2.16.840.1.101.3.4.1.46',
            ],
            [
                'name' => 'Key agreement',
                'asset_type' => 'algorithm',
                'current_algorithm' => 'X25519',
                'quantum_risk' => 'critical',
                'migration_status' => 'planned',
                'target_algorithm' => 'ML-KEM-768 (FIPS 203)',
                'primitive' => 'key_exchange',
                'classical_security_level' => '128 bits',
            ],
            [
                'name' => 'API key hashing',
                'asset_type' => 'algorithm',
                'current_algorithm' => 'SHA-256',
                'quantum_risk' => 'medium',
                'migration_status' => 'planned',
                'target_algorithm' => 'SHA-384',
                'primitive' => 'hash',
                'classical_security_level' => '128 bits',
                'oid' => '2.16.840.1.101.3.4.2.1',
            ],
        ] as $asset) {
            QbomAsset::query()->create([
                'bom_id' => $paymentsQbom->id,
                'version' => '1.0',
                'vendor' => 'Acme Digital',
                'origin' => 'India',
                'license' => 'Proprietary',
                'communication_protocol' => 'TLS 1.3',
                'hardware' => 'Cloud load balancer with HSM-backed key storage.',
                'software_dependencies' => 'OpenSSL 3.3, PHP 8.4 sodium extension.',
                'environmental_impact' => 'Shared multi-tenant infrastructure; energy use tracked centrally.',
                'vulnerabilities' => 'No open CVEs at the time of seeding.',
                'attestations' => 'Signed by the Acme Digital platform security team.',
                'algorithm_list' => 'AES-256-GCM, ECDSA P-256, SHA-256, X25519',
                ...$asset,
            ]);
        }

        $gatewaySbom = $this->bom($gateway, BomType::Sbom, 'Edge Gateway X1 — firmware', $user, BomStatus::Approved, '2026-08-20', [
            'standard' => 'SPDX',
            'standard_version' => '3.0',
        ], BomLevel::Delivery, BomClassification::Deployed);

        foreach ([
            ['busybox', '1.36.1', 'BusyBox', 'application', 'GPL-2.0', 'pkg:generic/busybox@1.36.1', 3],
            ['mosquitto', '2.0.18', 'Eclipse Foundation', 'application', 'EPL-2.0', 'pkg:generic/mosquitto@2.0.18', 1],
            ['dropbear', '2022.83', 'Dropbear SSH', 'application', 'MIT', 'pkg:generic/dropbear@2022.83', 2],
        ] as [$name, $version, $supplier, $type, $license, $purl, $vulnerabilities]) {
            SbomComponent::query()->create([
                'bom_id' => $gatewaySbom->id,
                'name' => $name,
                'version' => $version,
                'description' => 'Firmware component shipped with the Edge Gateway X1.',
                'supplier' => $supplier,
                'component_type' => $type,
                'scope' => 'required',
                'license' => $license,
                'origin' => 'open-source',
                'dependencies' => 'glibc, openssl',
                'patch_status' => $vulnerabilities > 0 ? 'update_available' : 'patched',
                'release_date' => '2025-11-01',
                'end_of_life' => '2027-12-31',
                'criticality' => $vulnerabilities > 1 ? 'high' : 'medium',
                'author' => 'Acme Networks Firmware Team',
                'is_executable' => true,
                'is_archive' => false,
                'is_structured' => false,
                'purl' => $purl,
                'hash_algorithm' => 'SHA-256',
                'hash_value' => hash('sha256', $name.$version),
                'vulnerabilities_count' => $vulnerabilities,
            ]);
        }

        $gatewayHbom = $this->bom($gateway, BomType::Hbom, 'Edge Gateway X1 — hardware', $user, BomStatus::Approved, '2026-08-20', [
            'standard' => 'CERT-In HBOM',
        ]);

        foreach ([
            [
                'NXP i.MX 8M Plus', '2.1', 'NXP', 'MIMX8ML8', 'processor', 1, '2.4.1', 'Germany', 'critical',
                'Quad-core Arm Cortex-A53 applications processor (1.6 GHz) with integrated NPU.',
                '3 years onsite, AMC until 2029', 'Eindhoven, Netherlands', '2026-02-11',
                'NXP Semiconductors', 'Eindhoven, Netherlands', 'SN-NXP-0001',
                '1.6 GHz, 8 MB L2 cache, 2.3 TOPS NPU', '28nm', 'RoHS, CE, FIPS 140-3 ready',
                '5V DC, 2A', 'Proprietary NXP licence', 'Passed thermal and EMC testing', '2029-12-31',
                'Monitored via CERT-In advisories; no open CVEs.',
            ],
            [
                'LPDDR4 memory', '1.0', 'Micron', 'MT53E1G32D2', 'memory', 1, null, 'United States', 'high',
                '32 Gb LPDDR4X memory for runtime workloads.',
                '3 years onsite', 'Boise, Idaho, USA', '2026-01-20',
                'Micron Technology', 'Boise, Idaho, USA', 'SN-MT-0002',
                '32 Gb, 4266 MT/s, 1.1V', '10nm', 'RoHS, CE',
                '1.1V DC', null, 'Passed memory stress test', '2029-12-31', null,
            ],
            [
                'eMMC storage', '1.0', 'Kingston', 'EMMC32G', 'storage', 1, null, 'Taiwan', 'high',
                '32 GB eMMC 5.1 storage for firmware and logs.',
                '3 years onsite', 'Taipei, Taiwan', '2026-01-25',
                'Kingston Technology', 'Taipei, Taiwan', 'SN-KG-0003',
                '32 GB, eMMC 5.1, HS400', '15nm', 'RoHS, CE',
                '3.3V DC', null, 'Passed endurance test', '2029-12-31', null,
            ],
            [
                'Marvell 88E1512', '1.0', 'Marvell', '88E1512', 'network', 2, null, 'United States', 'medium',
                'Dual-port Gigabit Ethernet PHY.',
                '3 years onsite', 'Santa Clara, California, USA', '2026-02-02',
                'Marvell Technology', 'Santa Clara, California, USA', 'SN-MV-0004',
                'Dual-port 10/100/1000BASE-T', '28nm', 'RoHS, CE',
                '1.8V DC', null, 'Passed signal integrity test', '2029-12-31', null,
            ],
        ] as [
            $name, $productVersion, $manufacturer, $model, $category, $quantity, $firmware, $origin,
            $criticality, $details, $warranty, $manufacturerLocation, $manufacturingDate, $supplierName,
            $supplierLocation, $serial, $specification, $technologyNode, $compliance, $powerSupply,
            $license, $testResult, $endOfLife, $vulnerabilities,
        ]) {
            HbomComponent::query()->create([
                'bom_id' => $gatewayHbom->id,
                'name' => $name,
                'product_version' => $productVersion,
                'product_details' => $details,
                'manufacturer' => $manufacturer,
                'manufacturer_location' => $manufacturerLocation,
                'manufacturing_date' => $manufacturingDate,
                'supplier_name' => $supplierName,
                'supplier_location' => $supplierLocation,
                'warranty' => $warranty,
                'model_number' => $model,
                'part_number' => $model,
                'serial_number' => $serial,
                'quantity' => $quantity,
                'category' => $category,
                'technical_specification' => $specification,
                'technology_node' => $technologyNode,
                'compliance' => $compliance,
                'power_supply' => $powerSupply,
                'license' => $license,
                'test_result' => $testResult,
                'firmware_version' => $firmware,
                'country_of_origin' => $origin,
                'end_of_life' => $endOfLife,
                'criticality' => $criticality,
                'vulnerabilities' => $vulnerabilities,
            ]);
        }

        $copilotAibom = $this->bom($copilot, BomType::Aibom, 'Support Copilot — AI assets', $user, BomStatus::InReview, '2026-10-18', [
            'standard' => 'CERT-In AIBOM',
        ]);

        foreach ([
            [
                'GPT-4.1', 'llm', 'OpenAI', '2026-04', 'Proprietary', 'Public support docs (2024-2026)',
                'Answer generation', 'limited', 'openai-php/client, laravel/framework',
                'Transformer (decoder-only) with retrieval augmentation', 'Accuracy 92%, F1 0.89',
                'Public web and internal knowledge base', 'Azure OpenAI GPT-4.1 deployment',
                'Customer support conversations', 'Text', 'Text', 'Answering customer support questions',
                'Legal, medical or financial advice',
            ],
            [
                'text-embedding-3-large', 'embedding', 'OpenAI', '3', 'Proprietary', 'Public support docs (2024-2026)',
                'Document retrieval', 'minimal', 'openai-php/client',
                'Transformer embedding model', 'Recall@10 0.94',
                'Public web and internal knowledge base', 'Azure OpenAI embedding deployment',
                'Support documents', 'Text', 'Vector (3072 dimensions)', 'Semantic search over support documents',
                'Facial recognition',
            ],
            [
                'Support docs corpus', 'dataset', 'Acme Digital', '2026.10', 'Internal', 'Zendesk exports',
                'Retrieval corpus', 'limited', 'PostgreSQL, pgvector',
                'Curated dataset', 'Coverage 87% of product documentation',
                'Zendesk ticket exports and product manuals', 'PostgreSQL 16 with pgvector',
                'Customer questions', 'Text', 'Text chunks', 'Grounding retrieval-augmented answers',
                'Training new models',
            ],
            [
                'PII redaction classifier', 'classifier', 'Acme Digital', '1.2', 'Proprietary', 'Anonymised tickets',
                'Redact personal data', 'high', 'scikit-learn, ONNX Runtime',
                'Linear SVM over character n-grams', 'Precision 0.97, recall 0.95',
                'Anonymised support tickets', 'CPU inference on application servers',
                'Support ticket text', 'Text', 'Entity spans (PII)', 'Redacting personal data before logging',
                'Making automated decisions about customers',
            ],
        ] as [
            $name, $type, $provider, $version, $license, $dataset, $purpose, $risk, $dependencies,
            $algorithms, $metrics, $dataSource, $hardware, $input, $modelInput, $modelOutput, $intended,
            $outOfScope,
        ]) {
            AibomAsset::query()->create([
                'bom_id' => $copilotAibom->id,
                'name' => $name,
                'model_type' => $type,
                'provider' => $provider,
                'version' => $version,
                'license' => $license,
                'software_dependencies' => $dependencies,
                'algorithms' => $algorithms,
                'performance_metrics' => $metrics,
                'data_source' => $dataSource,
                'dataset' => $dataset,
                'hardware' => $hardware,
                'security_requirements' => 'TLS 1.3 in transit, AES-256 at rest, role-based access control.',
                'model_input' => $modelInput,
                'model_output' => $modelOutput,
                'purpose' => $intended,
                'out_of_scope_usage' => $outOfScope,
                'environmental_impact' => 'Inference hosted on shared Azure regions; carbon intensity monitored monthly.',
                'risk_classification' => $risk,
                'evaluation' => 'Evaluated against the internal AI risk checklist.',
                'vulnerabilities' => 'Prompt-injection testing completed; no open findings.',
                'attestations' => 'Model card and AIBOM signed by the Acme Digital AI governance team.',
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function bom(
        Product $product,
        BomType $type,
        string $name,
        ?User $user,
        BomStatus $status,
        ?string $releasedAt,
        array $metadata,
        ?BomLevel $level = null,
        ?BomClassification $classification = null,
    ): Bom {
        return Bom::query()->create([
            'product_id' => $product->id,
            'created_by' => $user?->id,
            'type' => $type,
            'level' => $level,
            'classification' => $classification,
            'name' => $name,
            'version' => '1.0',
            'specification' => $type === BomType::Sbom ? 'CycloneDX 1.6' : null,
            'status' => $status,
            'description' => 'Demo data for the '.$type->getDescription().'.',
            'metadata' => $metadata,
            'released_at' => $releasedAt,
        ]);
    }
}

<?php

namespace App\Models;

use Database\Factories\QbomAssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QbomAsset extends Model
{
    /** @use HasFactory<QbomAssetFactory> */
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'name',
        'version',
        'vendor',
        'origin',
        'license',
        'communication_protocol',
        'hardware',
        'software_dependencies',
        'environmental_impact',
        'vulnerabilities',
        'attestations',
        'asset_type',
        'current_algorithm',
        'primitive',
        'mode',
        'crypto_functions',
        'classical_security_level',
        'oid',
        'algorithm_list',
        'key_identifier',
        'key_state',
        'key_size',
        'key_created_at',
        'key_activated_at',
        'protocol_version',
        'cipher_suites',
        'subject_name',
        'issuer_name',
        'not_valid_before',
        'not_valid_after',
        'signature_algorithm',
        'public_key_reference',
        'certificate_format',
        'certificate_extension',
        'quantum_risk',
        'migration_status',
        'target_algorithm',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'key_size' => 'integer',
            'key_created_at' => 'date',
            'key_activated_at' => 'date',
            'not_valid_before' => 'date',
            'not_valid_after' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Bom, $this>
     */
    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class);
    }
}

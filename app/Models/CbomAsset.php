<?php

namespace App\Models;

use Database\Factories\CbomAssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CbomAsset extends Model
{
    /** @use HasFactory<CbomAssetFactory> */
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'name',
        'asset_type',
        'algorithm',
        'primitive',
        'mode',
        'crypto_functions',
        'classical_security_level',
        'oid',
        'key_size',
        'key_identifier',
        'key_state',
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
        'quantum_safe',
        'provider',
        'location',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantum_safe' => 'boolean',
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

<?php

namespace App\Models;

use Database\Factories\SbomComponentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SbomComponent extends Model
{
    /** @use HasFactory<SbomComponentFactory> */
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'name',
        'version',
        'description',
        'supplier',
        'component_type',
        'scope',
        'license',
        'origin',
        'dependencies',
        'vulnerabilities',
        'patch_status',
        'release_date',
        'end_of_life',
        'criticality',
        'usage_restrictions',
        'author',
        'is_executable',
        'is_archive',
        'is_structured',
        'purl',
        'cpe',
        'hash_algorithm',
        'hash_value',
        'vulnerabilities_count',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'vulnerabilities_count' => 'integer',
            'release_date' => 'date',
            'end_of_life' => 'date',
            'is_executable' => 'boolean',
            'is_archive' => 'boolean',
            'is_structured' => 'boolean',
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

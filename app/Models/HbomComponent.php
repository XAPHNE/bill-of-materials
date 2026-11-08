<?php

namespace App\Models;

use Database\Factories\HbomComponentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HbomComponent extends Model
{
    /** @use HasFactory<HbomComponentFactory> */
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'parent_id',
        'name',
        'product_version',
        'product_details',
        'manufacturer',
        'manufacturer_location',
        'manufacturing_date',
        'supplier_name',
        'supplier_location',
        'warranty',
        'model_number',
        'part_number',
        'serial_number',
        'quantity',
        'category',
        'technical_specification',
        'technology_node',
        'compliance',
        'power_supply',
        'license',
        'test_result',
        'vulnerabilities',
        'firmware_version',
        'country_of_origin',
        'end_of_life',
        'criticality',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'manufacturing_date' => 'date',
            'end_of_life' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Bom, $this>
     */
    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class);
    }

    /**
     * @return BelongsTo<HbomComponent, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<HbomComponent, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}

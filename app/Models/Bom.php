<?php

namespace App\Models;

use App\Enums\BomClassification;
use App\Enums\BomLevel;
use App\Enums\BomStatus;
use App\Enums\BomType;
use Database\Factories\BomFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bom extends Model
{
    /** @use HasFactory<BomFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'created_by',
        'type',
        'level',
        'classification',
        'name',
        'version',
        'specification',
        'status',
        'description',
        'metadata',
        'released_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => BomType::class,
            'level' => BomLevel::class,
            'classification' => BomClassification::class,
            'status' => BomStatus::class,
            'metadata' => 'array',
            'released_at' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return HasMany<SbomComponent, $this>
     */
    public function sbomComponents(): HasMany
    {
        return $this->hasMany(SbomComponent::class);
    }

    /**
     * @return HasMany<QbomAsset, $this>
     */
    public function qbomAssets(): HasMany
    {
        return $this->hasMany(QbomAsset::class);
    }

    /**
     * @return HasMany<CbomAsset, $this>
     */
    public function cbomAssets(): HasMany
    {
        return $this->hasMany(CbomAsset::class);
    }

    /**
     * @return HasMany<AibomAsset, $this>
     */
    public function aibomAssets(): HasMany
    {
        return $this->hasMany(AibomAsset::class);
    }

    /**
     * @return HasMany<HbomComponent, $this>
     */
    public function hbomComponents(): HasMany
    {
        return $this->hasMany(HbomComponent::class)->whereNull('parent_id');
    }

    /**
     * Every hardware component, including sub-components of a recursive HBOM.
     *
     * @return HasMany<HbomComponent, $this>
     */
    public function allHbomComponents(): HasMany
    {
        return $this->hasMany(HbomComponent::class);
    }

    /**
     * @param  Builder<Bom>  $query
     */
    public function scopeOfType(Builder $query, BomType $type): void
    {
        $query->where('type', $type);
    }

    public function entryCount(): int
    {
        $countAttribute = match ($this->type) {
            BomType::Sbom => 'sbom_components_count',
            BomType::Qbom => 'qbom_assets_count',
            BomType::Cbom => 'cbom_assets_count',
            BomType::Aibom => 'aibom_assets_count',
            BomType::Hbom => 'hbom_components_count',
        };

        if (array_key_exists($countAttribute, $this->attributes)) {
            return (int) $this->attributes[$countAttribute];
        }

        return match ($this->type) {
            BomType::Sbom => $this->sbomComponents()->count(),
            BomType::Qbom => $this->qbomAssets()->count(),
            BomType::Cbom => $this->cbomAssets()->count(),
            BomType::Aibom => $this->aibomAssets()->count(),
            BomType::Hbom => $this->allHbomComponents()->count(),
        };
    }
}

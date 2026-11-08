<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'vendor',
        'version',
        'category',
        'description',
    ];

    /**
     * @return HasMany<Bom, $this>
     */
    public function boms(): HasMany
    {
        return $this->hasMany(Bom::class);
    }

    public function latestBom(): ?Bom
    {
        return $this->boms()->latest('released_at')->first();
    }
}

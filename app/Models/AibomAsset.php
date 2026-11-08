<?php

namespace App\Models;

use Database\Factories\AibomAssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AibomAsset extends Model
{
    /** @use HasFactory<AibomAssetFactory> */
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'name',
        'model_type',
        'provider',
        'version',
        'license',
        'software_dependencies',
        'algorithms',
        'performance_metrics',
        'data_source',
        'dataset',
        'hardware',
        'security_requirements',
        'model_input',
        'model_output',
        'purpose',
        'out_of_scope_usage',
        'environmental_impact',
        'risk_classification',
        'evaluation',
        'vulnerabilities',
        'attestations',
        'notes',
    ];

    /**
     * @return BelongsTo<Bom, $this>
     */
    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class);
    }
}

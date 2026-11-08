<?php

namespace App\Filament\Widgets;

use App\Enums\BomType;
use App\Models\AibomAsset;
use App\Models\Bom;
use App\Models\CbomAsset;
use App\Models\HbomComponent;
use App\Models\Product;
use App\Models\QbomAsset;
use App\Models\SbomComponent;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BomOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Products', Product::count())
                ->description('Products with bills of materials')
                ->icon('heroicon-o-cube')
                ->color('gray'),
            Stat::make('SBOMs', Bom::query()->where('type', BomType::Sbom)->count())
                ->description(SbomComponent::count().' software components')
                ->icon('heroicon-o-code-bracket-square')
                ->color('info'),
            Stat::make('CBOMs', Bom::query()->where('type', BomType::Cbom)->count())
                ->description(CbomAsset::count().' cryptographic assets')
                ->icon('heroicon-o-key')
                ->color('warning'),
            Stat::make('QBOMs', Bom::query()->where('type', BomType::Qbom)->count())
                ->description(QbomAsset::count().' quantum-vulnerable assets')
                ->icon('heroicon-o-bolt')
                ->color('purple'),
            Stat::make('AIBOMs', Bom::query()->where('type', BomType::Aibom)->count())
                ->description(AibomAsset::count().' AI assets')
                ->icon('heroicon-o-sparkles')
                ->color('success'),
            Stat::make('HBOMs', Bom::query()->where('type', BomType::Hbom)->count())
                ->description(HbomComponent::count().' hardware components')
                ->icon('heroicon-o-server-stack')
                ->color('gray'),
        ];
    }
}

<?php

namespace App\Filament\Resources\AibomAssets;

use App\Filament\Resources\AibomAssets\Pages\CreateAibomAsset;
use App\Filament\Resources\AibomAssets\Pages\EditAibomAsset;
use App\Filament\Resources\AibomAssets\Pages\ListAibomAssets;
use App\Filament\Resources\AibomAssets\Schemas\AibomAssetForm;
use App\Filament\Resources\AibomAssets\Tables\AibomAssetsTable;
use App\Models\AibomAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AibomAssetResource extends Resource
{
    protected static ?string $model = AibomAsset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Components';

    protected static ?string $navigationLabel = 'AI assets';

    protected static ?string $modelLabel = 'AI asset';

    protected static ?string $pluralModelLabel = 'AI assets';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AibomAssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AibomAssetsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAibomAssets::route('/'),
            'create' => CreateAibomAsset::route('/create'),
            'edit' => EditAibomAsset::route('/{record}/edit'),
        ];
    }
}

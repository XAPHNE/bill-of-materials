<?php

namespace App\Filament\Resources\QbomAssets;

use App\Filament\Resources\QbomAssets\Pages\CreateQbomAsset;
use App\Filament\Resources\QbomAssets\Pages\EditQbomAsset;
use App\Filament\Resources\QbomAssets\Pages\ListQbomAssets;
use App\Filament\Resources\QbomAssets\Schemas\QbomAssetForm;
use App\Filament\Resources\QbomAssets\Tables\QbomAssetsTable;
use App\Models\QbomAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class QbomAssetResource extends Resource
{
    protected static ?string $model = QbomAsset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|UnitEnum|null $navigationGroup = 'Components';

    protected static ?string $navigationLabel = 'Quantum assets';

    protected static ?string $modelLabel = 'quantum asset';

    protected static ?string $pluralModelLabel = 'quantum assets';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return QbomAssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QbomAssetsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQbomAssets::route('/'),
            'create' => CreateQbomAsset::route('/create'),
            'edit' => EditQbomAsset::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\CbomAssets;

use App\Filament\Resources\CbomAssets\Pages\CreateCbomAsset;
use App\Filament\Resources\CbomAssets\Pages\EditCbomAsset;
use App\Filament\Resources\CbomAssets\Pages\ListCbomAssets;
use App\Filament\Resources\CbomAssets\Schemas\CbomAssetForm;
use App\Filament\Resources\CbomAssets\Tables\CbomAssetsTable;
use App\Models\CbomAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CbomAssetResource extends Resource
{
    protected static ?string $model = CbomAsset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|UnitEnum|null $navigationGroup = 'Components';

    protected static ?string $navigationLabel = 'Cryptographic assets';

    protected static ?string $modelLabel = 'cryptographic asset';

    protected static ?string $pluralModelLabel = 'cryptographic assets';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CbomAssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CbomAssetsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCbomAssets::route('/'),
            'create' => CreateCbomAsset::route('/create'),
            'edit' => EditCbomAsset::route('/{record}/edit'),
        ];
    }
}

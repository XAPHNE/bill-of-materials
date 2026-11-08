<?php

namespace App\Filament\Resources\Boms;

use App\Enums\BomType;
use App\Filament\Resources\Boms\Pages\CreateBom;
use App\Filament\Resources\Boms\Pages\EditBom;
use App\Filament\Resources\Boms\Pages\ListBoms;
use App\Filament\Resources\Boms\RelationManagers\AibomAssetsRelationManager;
use App\Filament\Resources\Boms\RelationManagers\CbomAssetsRelationManager;
use App\Filament\Resources\Boms\RelationManagers\HbomComponentsRelationManager;
use App\Filament\Resources\Boms\RelationManagers\QbomAssetsRelationManager;
use App\Filament\Resources\Boms\RelationManagers\SbomComponentsRelationManager;
use App\Filament\Resources\Boms\Schemas\BomForm;
use App\Filament\Resources\Boms\Tables\BomsTable;
use App\Models\Bom;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class BomResource extends Resource
{
    protected static ?string $model = Bom::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Bill of Materials';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BomForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BomsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount([
            'sbomComponents',
            'qbomAssets',
            'cbomAssets',
            'aibomAssets',
            'allHbomComponents as hbom_components_count',
        ]);
    }

    public static function getRelations(): array
    {
        return [
            SbomComponentsRelationManager::class,
            QbomAssetsRelationManager::class,
            CbomAssetsRelationManager::class,
            AibomAssetsRelationManager::class,
            HbomComponentsRelationManager::class,
        ];
    }

    public static function getBomTypeOf(Model $record): ?BomType
    {
        return $record instanceof Bom ? $record->type : null;
    }

    public static function showsRelationFor(BomType $type, Model $record): bool
    {
        return static::getBomTypeOf($record) === $type;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBoms::route('/'),
            'create' => CreateBom::route('/create'),
            'edit' => EditBom::route('/{record}/edit'),
        ];
    }
}

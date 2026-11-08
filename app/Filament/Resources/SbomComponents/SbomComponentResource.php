<?php

namespace App\Filament\Resources\SbomComponents;

use App\Filament\Resources\SbomComponents\Pages\CreateSbomComponent;
use App\Filament\Resources\SbomComponents\Pages\EditSbomComponent;
use App\Filament\Resources\SbomComponents\Pages\ListSbomComponents;
use App\Filament\Resources\SbomComponents\Schemas\SbomComponentForm;
use App\Filament\Resources\SbomComponents\Tables\SbomComponentsTable;
use App\Models\SbomComponent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SbomComponentResource extends Resource
{
    protected static ?string $model = SbomComponent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracketSquare;

    protected static string|UnitEnum|null $navigationGroup = 'Components';

    protected static ?string $navigationLabel = 'Software components';

    protected static ?string $modelLabel = 'software component';

    protected static ?string $pluralModelLabel = 'software components';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SbomComponentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SbomComponentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSbomComponents::route('/'),
            'create' => CreateSbomComponent::route('/create'),
            'edit' => EditSbomComponent::route('/{record}/edit'),
        ];
    }
}

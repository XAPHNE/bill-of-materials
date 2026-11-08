<?php

namespace App\Filament\Resources\HbomComponents;

use App\Filament\Resources\HbomComponents\Pages\CreateHbomComponent;
use App\Filament\Resources\HbomComponents\Pages\EditHbomComponent;
use App\Filament\Resources\HbomComponents\Pages\ListHbomComponents;
use App\Filament\Resources\HbomComponents\Schemas\HbomComponentForm;
use App\Filament\Resources\HbomComponents\Tables\HbomComponentsTable;
use App\Models\HbomComponent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HbomComponentResource extends Resource
{
    protected static ?string $model = HbomComponent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedServerStack;

    protected static string|UnitEnum|null $navigationGroup = 'Components';

    protected static ?string $navigationLabel = 'Hardware components';

    protected static ?string $modelLabel = 'hardware component';

    protected static ?string $pluralModelLabel = 'hardware components';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return HbomComponentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HbomComponentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHbomComponents::route('/'),
            'create' => CreateHbomComponent::route('/create'),
            'edit' => EditHbomComponent::route('/{record}/edit'),
        ];
    }
}

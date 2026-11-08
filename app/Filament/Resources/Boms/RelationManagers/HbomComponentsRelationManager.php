<?php

namespace App\Filament\Resources\Boms\RelationManagers;

use App\Enums\BomType;
use App\Filament\Resources\Boms\BomResource;
use App\Filament\Resources\HbomComponents\Schemas\HbomComponentForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HbomComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'hbomComponents';

    protected static ?string $title = 'Hardware components';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return BomResource::showsRelationFor(BomType::Hbom, $ownerRecord);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components(HbomComponentForm::fields());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('manufacturer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('model_number')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('category')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('firmware_version')
                    ->toggleable(),
                TextColumn::make('criticality')
                    ->color(fn (string $state): string => match ($state) {
                        'critical' => 'danger',
                        'high' => 'warning',
                        'medium' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('end_of_life')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

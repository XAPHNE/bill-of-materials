<?php

namespace App\Filament\Resources\Boms\RelationManagers;

use App\Enums\BomType;
use App\Filament\Resources\Boms\BomResource;
use App\Filament\Resources\SbomComponents\Schemas\SbomComponentForm;
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

class SbomComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'sbomComponents';

    protected static ?string $title = 'Software components';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return BomResource::showsRelationFor(BomType::Sbom, $ownerRecord);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components(SbomComponentForm::fields());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('version')
                    ->sortable(),
                TextColumn::make('supplier')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('component_type')
                    ->sortable(),
                TextColumn::make('scope')
                    ->color(fn (string $state): string => match ($state) {
                        'required' => 'success',
                        'optional' => 'gray',
                        default => 'danger',
                    }),
                TextColumn::make('license')
                    ->searchable(),
                TextColumn::make('vulnerabilities_count')
                    ->label('Vulnerabilities')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'danger' : 'success')
                    ->sortable(),
                TextColumn::make('criticality')
                    ->color(fn (?string $state): string => match ($state) {
                        'critical' => 'danger',
                        'high' => 'warning',
                        'medium' => 'info',
                        'low' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('patch_status')
                    ->label('Patch status')
                    ->toggleable(),
                TextColumn::make('purl')
                    ->label('purl')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

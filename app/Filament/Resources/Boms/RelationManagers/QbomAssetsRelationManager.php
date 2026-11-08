<?php

namespace App\Filament\Resources\Boms\RelationManagers;

use App\Enums\BomType;
use App\Filament\Resources\Boms\BomResource;
use App\Filament\Resources\QbomAssets\Schemas\QbomAssetForm;
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

class QbomAssetsRelationManager extends RelationManager
{
    protected static string $relationship = 'qbomAssets';

    protected static ?string $title = 'Quantum-vulnerable assets';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return BomResource::showsRelationFor(BomType::Qbom, $ownerRecord);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components(QbomAssetForm::fields());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_type')
                    ->sortable(),
                TextColumn::make('current_algorithm')
                    ->searchable(),
                TextColumn::make('quantum_risk')
                    ->color(fn (string $state): string => match ($state) {
                        'critical' => 'danger',
                        'high' => 'warning',
                        'medium' => 'info',
                        'low' => 'gray',
                        'none' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('migration_status')
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'info',
                        'planned' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('target_algorithm')
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

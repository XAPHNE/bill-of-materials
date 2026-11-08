<?php

namespace App\Filament\Resources\Boms\RelationManagers;

use App\Enums\BomType;
use App\Filament\Resources\AibomAssets\Schemas\AibomAssetForm;
use App\Filament\Resources\Boms\BomResource;
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

class AibomAssetsRelationManager extends RelationManager
{
    protected static string $relationship = 'aibomAssets';

    protected static ?string $title = 'AI models, datasets and components';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return BomResource::showsRelationFor(BomType::Aibom, $ownerRecord);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components(AibomAssetForm::fields());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('model_type')
                    ->sortable(),
                TextColumn::make('provider')
                    ->searchable(),
                TextColumn::make('version')
                    ->sortable(),
                TextColumn::make('license')
                    ->toggleable(),
                TextColumn::make('risk_classification')
                    ->color(fn (string $state): string => match ($state) {
                        'minimal' => 'success',
                        'limited' => 'info',
                        'high' => 'warning',
                        'unacceptable' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('purpose')
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

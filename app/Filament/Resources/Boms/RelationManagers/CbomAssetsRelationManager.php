<?php

namespace App\Filament\Resources\Boms\RelationManagers;

use App\Enums\BomType;
use App\Filament\Resources\Boms\BomResource;
use App\Filament\Resources\CbomAssets\Schemas\CbomAssetForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CbomAssetsRelationManager extends RelationManager
{
    protected static string $relationship = 'cbomAssets';

    protected static ?string $title = 'Cryptographic assets';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return BomResource::showsRelationFor(BomType::Cbom, $ownerRecord);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components(CbomAssetForm::fields());
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
                TextColumn::make('algorithm')
                    ->searchable(),
                TextColumn::make('primitive')
                    ->color('gray'),
                TextColumn::make('key_size')
                    ->label('Key size')
                    ->suffix(' bits')
                    ->sortable(),
                IconColumn::make('quantum_safe')
                    ->label('Quantum-safe')
                    ->boolean(),
                TextColumn::make('provider')
                    ->toggleable(),
                TextColumn::make('location')
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

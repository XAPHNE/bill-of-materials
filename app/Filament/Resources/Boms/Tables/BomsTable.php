<?php

namespace App\Filament\Resources\Boms\Tables;

use App\Enums\BomClassification;
use App\Enums\BomLevel;
use App\Enums\BomStatus;
use App\Enums\BomType;
use App\Filament\Actions\ExportBomXlsxAction;
use App\Models\Bom;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->color(fn (BomType $state): string => $state->getColor())
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('version')
                    ->sortable(),
                TextColumn::make('level')
                    ->label('SBOM level')
                    ->formatStateUsing(fn (?BomLevel $state): ?string => $state?->getLabel())
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('classification')
                    ->label('SBOM classification')
                    ->formatStateUsing(fn (?BomClassification $state): ?string => $state?->getLabel())
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->color(fn (BomStatus $state): string => $state->getColor())
                    ->sortable(),
                TextColumn::make('entries')
                    ->state(fn (Bom $record): int => $record->entryCount())
                    ->badge()
                    ->color('info')
                    ->alignCenter(),
                TextColumn::make('specification')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('released_at')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(BomType::class)
                    ->multiple(),
                SelectFilter::make('status')
                    ->options(BomStatus::class),
                SelectFilter::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ExportBomXlsxAction::make(),
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

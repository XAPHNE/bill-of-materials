<?php

namespace App\Filament\Resources\AibomAssets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AibomAssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bom.name')
                    ->label('AIBOM')
                    ->searchable()
                    ->toggleable(),
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
            ])
            ->filters([
                SelectFilter::make('model_type')
                    ->options([
                        'llm' => 'Large language model',
                        'embedding' => 'Embedding model',
                        'classifier' => 'Classifier',
                        'vision' => 'Vision model',
                        'speech' => 'Speech model',
                        'dataset' => 'Dataset',
                        'agent' => 'Agent / orchestration',
                        'other' => 'Other',
                    ]),
                SelectFilter::make('risk_classification')
                    ->options([
                        'minimal' => 'Minimal risk',
                        'limited' => 'Limited risk',
                        'high' => 'High risk',
                        'unacceptable' => 'Unacceptable risk',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

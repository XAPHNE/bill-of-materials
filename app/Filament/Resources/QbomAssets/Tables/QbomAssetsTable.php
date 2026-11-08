<?php

namespace App\Filament\Resources\QbomAssets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QbomAssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bom.name')
                    ->label('QBOM')
                    ->searchable()
                    ->toggleable(),
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
            ->filters([
                SelectFilter::make('quantum_risk')
                    ->options([
                        'critical' => 'Critical',
                        'high' => 'High',
                        'medium' => 'Medium',
                        'low' => 'Low',
                        'none' => 'None',
                        'unknown' => 'Unknown',
                    ]),
                SelectFilter::make('migration_status')
                    ->options([
                        'not_started' => 'Not started',
                        'planned' => 'Planned',
                        'in_progress' => 'In progress',
                        'completed' => 'Completed',
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

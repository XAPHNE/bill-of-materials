<?php

namespace App\Filament\Resources\HbomComponents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HbomComponentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bom.name')
                    ->label('HBOM')
                    ->searchable()
                    ->toggleable(),
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
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'processor' => 'Processor',
                        'memory' => 'Memory',
                        'storage' => 'Storage',
                        'network' => 'Network',
                        'sensor' => 'Sensor',
                        'power' => 'Power',
                        'other' => 'Other',
                    ]),
                SelectFilter::make('criticality')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'critical' => 'Critical',
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

<?php

namespace App\Filament\Resources\CbomAssets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CbomAssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bom.name')
                    ->label('CBOM')
                    ->searchable()
                    ->toggleable(),
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
            ])
            ->filters([
                SelectFilter::make('asset_type')
                    ->options([
                        'algorithm' => 'Algorithm',
                        'key' => 'Key',
                        'certificate' => 'Certificate',
                        'protocol' => 'Protocol',
                        'library' => 'Library',
                        'other' => 'Other',
                    ]),
                TernaryFilter::make('quantum_safe')
                    ->label('Quantum-safe'),
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

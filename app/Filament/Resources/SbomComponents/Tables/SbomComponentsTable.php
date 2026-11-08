<?php

namespace App\Filament\Resources\SbomComponents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SbomComponentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('bom.name')
                    ->label('SBOM')
                    ->searchable()
                    ->toggleable(),
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
            ])
            ->filters([
                SelectFilter::make('component_type')
                    ->options([
                        'application' => 'Application',
                        'framework' => 'Framework',
                        'library' => 'Library',
                        'container' => 'Container image',
                        'operating-system' => 'Operating system',
                        'firmware' => 'Firmware',
                        'file' => 'File',
                        'other' => 'Other',
                    ]),
                SelectFilter::make('scope')
                    ->options([
                        'required' => 'Required',
                        'optional' => 'Optional',
                        'excluded' => 'Excluded',
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

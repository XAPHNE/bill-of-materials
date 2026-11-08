<?php

namespace App\Filament\Resources\Boms\Schemas;

use App\Enums\BomClassification;
use App\Enums\BomLevel;
use App\Enums\BomStatus;
use App\Enums\BomType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identification')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('type')
                            ->options(BomType::class)
                            ->required()
                            ->live(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('version')
                            ->required()
                            ->default('1.0')
                            ->maxLength(255),
                        Select::make('level')
                            ->options(BomLevel::class)
                            ->required(fn (Get $get): bool => $get('type') === BomType::Sbom)
                            ->helperText('CERT-In SBOM level (Top-level, n-level, Delivery, Transitive or Complete).')
                            ->visible(fn (Get $get): bool => $get('type') === BomType::Sbom),
                        Select::make('classification')
                            ->options(BomClassification::class)
                            ->required(fn (Get $get): bool => $get('type') === BomType::Sbom)
                            ->helperText('SDLC stage this SBOM was captured at.')
                            ->visible(fn (Get $get): bool => $get('type') === BomType::Sbom),
                        TextInput::make('specification')
                            ->placeholder('e.g. CycloneDX 1.6, SPDX 3.0, CERT-In SBOM 1.1')
                            ->maxLength(255),
                        Select::make('status')
                            ->options(BomStatus::class)
                            ->default(BomStatus::Draft)
                            ->required(),
                        DatePicker::make('released_at'),
                        Select::make('created_by')
                            ->relationship('creator', 'name')
                            ->searchable()
                            ->default(fn () => auth()->id()),
                    ])
                    ->columns(2),
                Section::make('Description')
                    ->schema([
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        KeyValue::make('metadata')
                            ->label('Additional metadata')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

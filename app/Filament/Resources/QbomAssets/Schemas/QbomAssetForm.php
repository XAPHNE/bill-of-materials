<?php

namespace App\Filament\Resources\QbomAssets\Schemas;

use App\Filament\Schemas\CryptographicAssetFields;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QbomAssetForm
{
    /**
     * CERT-In QBOM minimum elements (Table 8, version 2.0).
     *
     * @return array<int, Component>
     */
    public static function fields(): array
    {
        return [
            Section::make('Quantum model / system')
                ->schema([
                    TextInput::make('name')
                        ->label('Model name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('version')
                        ->maxLength(255),
                    TextInput::make('vendor')
                        ->label('Vendor')
                        ->maxLength(255),
                    TextInput::make('origin')
                        ->label('Origin')
                        ->maxLength(255),
                    TextInput::make('license')
                        ->label('License information')
                        ->maxLength(255),
                    TextInput::make('communication_protocol')
                        ->label('Communication protocol')
                        ->placeholder('e.g. QKD ETSI GS QKD 014, TLS 1.3')
                        ->maxLength(255),
                    Textarea::make('hardware')
                        ->label('Hardware')
                        ->rows(2)
                        ->columnSpanFull(),
                    Textarea::make('software_dependencies')
                        ->label('Software dependencies')
                        ->rows(2)
                        ->columnSpanFull(),
                    Textarea::make('environmental_impact')
                        ->label('Environmental impact')
                        ->rows(2)
                        ->columnSpanFull(),
                    Textarea::make('vulnerabilities')
                        ->label('Vulnerabilities')
                        ->rows(2)
                        ->columnSpanFull(),
                    Textarea::make('attestations')
                        ->label('Attestations (signature / integrity)')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Cryptographic asset and quantum readiness')
                ->schema([
                    Select::make('asset_type')
                        ->options([
                            'algorithm' => 'Algorithm',
                            'key' => 'Key',
                            'protocol' => 'Protocol',
                            'service' => 'Service',
                            'certificate' => 'Certificate',
                            'other' => 'Other',
                        ])
                        ->default('algorithm')
                        ->required(),
                    TextInput::make('current_algorithm')
                        ->label('Algorithm name')
                        ->placeholder('e.g. RSA-2048, ECDSA P-256, AES-256')
                        ->maxLength(255),
                    Select::make('quantum_risk')
                        ->options([
                            'critical' => 'Critical',
                            'high' => 'High',
                            'medium' => 'Medium',
                            'low' => 'Low',
                            'none' => 'None (quantum-safe)',
                            'unknown' => 'Unknown',
                        ])
                        ->default('unknown')
                        ->required(),
                    Select::make('migration_status')
                        ->options([
                            'not_started' => 'Not started',
                            'planned' => 'Planned',
                            'in_progress' => 'In progress',
                            'completed' => 'Completed',
                        ])
                        ->default('not_started')
                        ->required(),
                    TextInput::make('target_algorithm')
                        ->placeholder('e.g. ML-KEM (FIPS 203), ML-DSA (FIPS 204)')
                        ->maxLength(255),
                ])
                ->columns(2),
            ...CryptographicAssetFields::sections(null),
            Section::make('Algorithm inventory')
                ->schema([
                    Textarea::make('algorithm_list')
                        ->label('Algorithms employed')
                        ->helperText('List every cryptographic algorithm used by the quantum device or system.')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
            Section::make('Notes')
                ->schema([
                    Textarea::make('notes')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('bom_id')
                ->label('QBOM')
                ->relationship('bom', 'name', modifyQueryUsing: fn ($query) => $query->where('type', 'qbom'))
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull(),
            ...static::fields(),
        ]);
    }
}

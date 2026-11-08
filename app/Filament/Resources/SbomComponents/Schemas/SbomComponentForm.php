<?php

namespace App\Filament\Resources\SbomComponents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SbomComponentForm
{
    /**
     * CERT-In SBOM minimum data fields (Table 5, version 2.0).
     *
     * @return array<int, Component>
     */
    public static function fields(): array
    {
        return [
            Section::make('Component identification')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('version')
                        ->maxLength(255),
                    TextInput::make('supplier')
                        ->maxLength(255),
                    Textarea::make('description')
                        ->rows(2)
                        ->columnSpanFull(),
                    Select::make('component_type')
                        ->options([
                            'application' => 'Application',
                            'framework' => 'Framework',
                            'library' => 'Library',
                            'container' => 'Container image',
                            'operating-system' => 'Operating system',
                            'firmware' => 'Firmware',
                            'file' => 'File',
                            'other' => 'Other',
                        ])
                        ->default('library')
                        ->required(),
                    Select::make('scope')
                        ->options([
                            'required' => 'Required',
                            'optional' => 'Optional',
                            'excluded' => 'Excluded',
                        ])
                        ->default('required')
                        ->required(),
                    TextInput::make('origin')
                        ->placeholder('e.g. open-source, proprietary, third-party vendor')
                        ->maxLength(255),
                    TextInput::make('license')
                        ->maxLength(255),
                ])
                ->columns(2),
            Section::make('Dependency, lifecycle and risk')
                ->schema([
                    Textarea::make('dependencies')
                        ->label('Component dependencies')
                        ->rows(2)
                        ->columnSpanFull(),
                    Textarea::make('vulnerabilities')
                        ->label('Vulnerability references')
                        ->placeholder('e.g. CVE-2024-1234 (high), CVE-2024-5678 (medium)')
                        ->rows(2)
                        ->columnSpanFull(),
                    Select::make('patch_status')
                        ->options([
                            'patched' => 'Patched',
                            'update_available' => 'Update available',
                            'unpatched' => 'Unpatched',
                            'not_applicable' => 'Not applicable',
                        ]),
                    Select::make('criticality')
                        ->options([
                            'critical' => 'Critical',
                            'high' => 'High',
                            'medium' => 'Medium',
                            'low' => 'Low',
                        ]),
                    DatePicker::make('release_date'),
                    DatePicker::make('end_of_life'),
                    TextInput::make('vulnerabilities_count')
                        ->label('Known vulnerabilities')
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                    Textarea::make('usage_restrictions')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Integrity and properties')
                ->schema([
                    TextInput::make('purl')
                        ->label('Unique identifier (purl)')
                        ->placeholder('pkg:composer/laravel/framework@13.40.0')
                        ->maxLength(255),
                    TextInput::make('cpe')
                        ->label('CPE')
                        ->maxLength(255),
                    Select::make('hash_algorithm')
                        ->options([
                            'SHA-256' => 'SHA-256',
                            'SHA-512' => 'SHA-512',
                            'SHA-1' => 'SHA-1',
                            'MD5' => 'MD5',
                        ]),
                    TextInput::make('hash_value')
                        ->maxLength(255),
                    TextInput::make('author')
                        ->label('Author of SBOM data')
                        ->maxLength(255),
                    Toggle::make('is_executable')
                        ->label('Executable property'),
                    Toggle::make('is_archive')
                        ->label('Archive property'),
                    Toggle::make('is_structured')
                        ->label('Structured property'),
                ])
                ->columns(2),
            Section::make('Comments')
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
                ->label('SBOM')
                ->relationship('bom', 'name', modifyQueryUsing: fn ($query) => $query->where('type', 'sbom'))
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull(),
            ...static::fields(),
        ]);
    }
}

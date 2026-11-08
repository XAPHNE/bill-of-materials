<?php

namespace App\Filament\Resources\HbomComponents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HbomComponentForm
{
    /**
     * CERT-In HBOM minimum elements (Table 11, version 2.0).
     *
     * @return array<int, Component>
     */
    public static function fields(): array
    {
        return [
            Section::make('Product')
                ->schema([
                    Select::make('parent_id')
                        ->label('Parent component')
                        ->relationship('parent', 'name', modifyQueryUsing: fn ($query) => $query->whereNull('parent_id'))
                        ->searchable()
                        ->preload()
                        ->placeholder('None (top-level hardware product)')
                        ->helperText('Select a parent to model a sub-component (recursive HBOM).'),
                    TextInput::make('name')
                        ->label('Product name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('product_version')
                        ->label('Product version')
                        ->maxLength(255),
                    TextInput::make('model_number')
                        ->label('Model number')
                        ->maxLength(255),
                    TextInput::make('part_number')
                        ->label('Part number')
                        ->maxLength(255),
                    TextInput::make('serial_number')
                        ->label('Serial number')
                        ->maxLength(255),
                    TextInput::make('quantity')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->required(),
                    Select::make('category')
                        ->options([
                            'processor' => 'Processor',
                            'memory' => 'Memory',
                            'storage' => 'Storage',
                            'network' => 'Network',
                            'sensor' => 'Sensor',
                            'power' => 'Power',
                            'other' => 'Other',
                        ])
                        ->default('other')
                        ->required(),
                    Textarea::make('product_details')
                        ->label('Product details')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Manufacturer, supplier and warranty')
                ->schema([
                    TextInput::make('manufacturer')
                        ->label('Manufacturer name')
                        ->maxLength(255),
                    TextInput::make('manufacturer_location')
                        ->label('Manufacturer location')
                        ->maxLength(255),
                    DatePicker::make('manufacturing_date')
                        ->label('Manufacturing date'),
                    TextInput::make('supplier_name')
                        ->label('Supplier information')
                        ->maxLength(255),
                    TextInput::make('supplier_location')
                        ->label('Supplier location')
                        ->maxLength(255),
                    TextInput::make('warranty')
                        ->label('Warranty / AMC information')
                        ->placeholder('e.g. 3 years onsite, AMC until 2029')
                        ->maxLength(255),
                    TextInput::make('country_of_origin')
                        ->label('Country of origin')
                        ->maxLength(255),
                    TextInput::make('firmware_version')
                        ->label('Firmware version')
                        ->maxLength(255),
                ])
                ->columns(2),
            Section::make('Technical specification and compliance')
                ->schema([
                    Textarea::make('technical_specification')
                        ->label('Technical specification')
                        ->placeholder('e.g. voltage, frequency, capacity')
                        ->rows(2)
                        ->columnSpanFull(),
                    TextInput::make('technology_node')
                        ->label('Technology node')
                        ->placeholder('e.g. 7nm, 14nm')
                        ->maxLength(255),
                    TextInput::make('power_supply')
                        ->label('Power supply')
                        ->placeholder('e.g. 12V DC, 2A, 24W')
                        ->maxLength(255),
                    TextInput::make('license')
                        ->label('License information')
                        ->maxLength(255),
                    Textarea::make('compliance')
                        ->label('Compliance')
                        ->placeholder('e.g. RoHS, CE, FIPS 140-3')
                        ->rows(2)
                        ->columnSpanFull(),
                    Textarea::make('test_result')
                        ->label('Test result')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Risk and lifecycle')
                ->schema([
                    Select::make('criticality')
                        ->options([
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High',
                            'critical' => 'Critical',
                        ])
                        ->default('medium')
                        ->required(),
                    DatePicker::make('end_of_life'),
                    Textarea::make('vulnerabilities')
                        ->label('Vulnerabilities')
                        ->placeholder('Known CVEs, severity ratings and security advisory references.')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->columns(2),
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
                ->label('HBOM')
                ->relationship('bom', 'name', modifyQueryUsing: fn ($query) => $query->where('type', 'hbom'))
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull(),
            ...static::fields(),
        ]);
    }
}

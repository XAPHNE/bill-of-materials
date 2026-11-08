<?php

namespace App\Filament\Resources\CbomAssets\Schemas;

use App\Filament\Schemas\CryptographicAssetFields;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CbomAssetForm
{
    /**
     * CERT-In CBOM minimum elements per cryptographic asset type (Tables 8 & 9, version 2.0).
     *
     * @return array<int, Component>
     */
    public static function fields(): array
    {
        return [
            Section::make('Cryptographic asset')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Select::make('asset_type')
                        ->options([
                            'algorithm' => 'Algorithm',
                            'key' => 'Key',
                            'certificate' => 'Certificate',
                            'protocol' => 'Protocol',
                            'library' => 'Library',
                            'other' => 'Other',
                        ])
                        ->default('algorithm')
                        ->required()
                        ->live(),
                    TextInput::make('provider')
                        ->placeholder('e.g. OpenSSL, BoringSSL, HSM vendor')
                        ->maxLength(255),
                    TextInput::make('location')
                        ->placeholder('e.g. src/Http/Kernel.php, load balancer, HSM')
                        ->maxLength(255),
                    Toggle::make('quantum_safe')
                        ->label('Quantum-safe'),
                ])
                ->columns(2),
            ...CryptographicAssetFields::sections(),
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
                ->label('CBOM')
                ->relationship('bom', 'name', modifyQueryUsing: fn ($query) => $query->where('type', 'cbom'))
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull(),
            ...static::fields(),
        ]);
    }
}

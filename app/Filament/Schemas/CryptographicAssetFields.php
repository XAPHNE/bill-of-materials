<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

/**
 * CERT-In Table 9 cryptographic asset elements, shared by CBOM and QBOM forms.
 */
class CryptographicAssetFields
{
    /**
     * @return array<int, Section>
     */
    public static function sections(?string $algorithmField = 'algorithm'): array
    {
        return [
            Section::make('Algorithm / protocol')
                ->visible(fn (Get $get): bool => in_array($get('asset_type'), ['algorithm', 'protocol'], true))
                ->schema([
                    ...($algorithmField === null ? [] : [
                        TextInput::make($algorithmField)
                            ->label('Algorithm name')
                            ->placeholder('e.g. AES-128-GCM, SHA512withRSA')
                            ->maxLength(255)
                            ->visible(fn (Get $get): bool => $get('asset_type') === 'algorithm'),
                    ]),
                    Select::make('primitive')
                        ->options([
                            'encryption' => 'Encryption',
                            'signature' => 'Digital signature',
                            'key_exchange' => 'Key exchange',
                            'hash' => 'Hash',
                            'mac' => 'Message authentication',
                            'key_derivation' => 'Key derivation',
                            'random_number_generation' => 'Random number generation',
                        ])
                        ->visible(fn (Get $get): bool => $get('asset_type') === 'algorithm'),
                    TextInput::make('mode')
                        ->placeholder('e.g. gcm, cbc')
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => $get('asset_type') === 'algorithm'),
                    TextInput::make('classical_security_level')
                        ->placeholder('e.g. 128 bits')
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => $get('asset_type') === 'algorithm'),
                    Textarea::make('crypto_functions')
                        ->label('Crypto functions')
                        ->rows(2)
                        ->columnSpanFull()
                        ->visible(fn (Get $get): bool => $get('asset_type') === 'algorithm'),
                    TextInput::make('protocol_version')
                        ->placeholder('e.g. TLS 1.3')
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => $get('asset_type') === 'protocol'),
                    Textarea::make('cipher_suites')
                        ->rows(2)
                        ->columnSpanFull()
                        ->visible(fn (Get $get): bool => $get('asset_type') === 'protocol'),
                    TextInput::make('oid')
                        ->label('OID')
                        ->placeholder('e.g. 2.16.840.1.101.3.4.1.6')
                        ->maxLength(255),
                ])
                ->columns(2),
            Section::make('Key')
                ->visible(fn (Get $get): bool => $get('asset_type') === 'key')
                ->schema([
                    TextInput::make('key_identifier')
                        ->label('Key ID')
                        ->maxLength(255),
                    Select::make('key_state')
                        ->options([
                            'active' => 'Active',
                            'revoked' => 'Revoked',
                            'expired' => 'Expired',
                            'pre_activation' => 'Pre-activation',
                        ]),
                    TextInput::make('key_size')
                        ->numeric()
                        ->minValue(0)
                        ->suffix('bits'),
                    DatePicker::make('key_created_at'),
                    DatePicker::make('key_activated_at'),
                ])
                ->columns(2),
            Section::make('Certificate')
                ->visible(fn (Get $get): bool => $get('asset_type') === 'certificate')
                ->schema([
                    TextInput::make('subject_name')
                        ->maxLength(255),
                    TextInput::make('issuer_name')
                        ->maxLength(255),
                    DatePicker::make('not_valid_before'),
                    DatePicker::make('not_valid_after'),
                    TextInput::make('signature_algorithm')
                        ->maxLength(255),
                    TextInput::make('public_key_reference')
                        ->maxLength(255),
                    TextInput::make('certificate_format')
                        ->placeholder('e.g. X.509')
                        ->maxLength(255),
                    TextInput::make('certificate_extension')
                        ->placeholder('e.g. .crt')
                        ->maxLength(255),
                ])
                ->columns(2),
        ];
    }
}

<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product details')
                    ->description('The product or system these bills of materials describe.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('vendor')
                            ->maxLength(255),
                        TextInput::make('version')
                            ->maxLength(255),
                        Select::make('category')
                            ->options([
                                'software' => 'Software',
                                'hardware' => 'Hardware',
                                'firmware' => 'Firmware',
                                'cloud-service' => 'Cloud service',
                                'ai-system' => 'AI system',
                                'other' => 'Other',
                            ])
                            ->searchable(),
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}

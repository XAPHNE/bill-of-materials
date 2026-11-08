<?php

namespace App\Filament\Resources\AibomAssets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AibomAssetForm
{
    /**
     * CERT-In AIBOM minimum elements (Table 10, version 2.0).
     *
     * @return array<int, Component>
     */
    public static function fields(): array
    {
        return [
            Section::make('Model identification')
                ->schema([
                    TextInput::make('name')
                        ->label('Model name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('version')
                        ->label('Model version')
                        ->maxLength(255),
                    Select::make('model_type')
                        ->options([
                            'llm' => 'Large language model (text generation)',
                            'embedding' => 'Embedding model',
                            'classifier' => 'Classifier',
                            'vision' => 'Vision / image processing',
                            'speech' => 'Speech model',
                            'dataset' => 'Dataset',
                            'agent' => 'Agent / orchestration',
                            'other' => 'Other',
                        ])
                        ->default('llm')
                        ->required(),
                    TextInput::make('provider')
                        ->label('Model developer')
                        ->maxLength(255),
                    TextInput::make('license')
                        ->label('Model licensing information')
                        ->placeholder('e.g. Apache-2.0, GPL-3.0, proprietary')
                        ->maxLength(255),
                    TextInput::make('purpose')
                        ->label('Intended usage')
                        ->maxLength(255),
                    Select::make('risk_classification')
                        ->options([
                            'minimal' => 'Minimal risk',
                            'limited' => 'Limited risk',
                            'high' => 'High risk',
                            'unacceptable' => 'Unacceptable risk',
                        ])
                        ->default('limited')
                        ->required(),
                ])
                ->columns(2),
            Section::make('Dependencies and algorithms')
                ->schema([
                    Textarea::make('software_dependencies')
                        ->label('Software dependencies')
                        ->placeholder('Libraries, frameworks, OS requirements and proprietary software required to run the model.')
                        ->rows(3)
                        ->columnSpanFull(),
                    Textarea::make('algorithms')
                        ->label('ML models and algorithms')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
            Section::make('Performance and data')
                ->schema([
                    Textarea::make('performance_metrics')
                        ->label('Model performance metrics')
                        ->placeholder('e.g. accuracy, precision, recall, F1 score')
                        ->rows(3)
                        ->columnSpanFull(),
                    TextInput::make('data_source')
                        ->label('Data source')
                        ->placeholder('e.g. proprietary, public, real-time, synthetic')
                        ->maxLength(255),
                    TextInput::make('dataset')
                        ->label('Data sets')
                        ->placeholder('Name, version and format of the training datasets')
                        ->maxLength(255),
                    Textarea::make('hardware')
                        ->label('Hardware')
                        ->placeholder('GPUs, TPUs, storage and memory required to run the model.')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Security, interfaces and lifecycle')
                ->schema([
                    Textarea::make('security_requirements')
                        ->label('Security requirements')
                        ->rows(2)
                        ->columnSpanFull(),
                    TextInput::make('model_input')
                        ->label('Input')
                        ->placeholder('e.g. text, images, audio')
                        ->maxLength(255),
                    TextInput::make('model_output')
                        ->label('Output')
                        ->placeholder('e.g. text, images, labels')
                        ->maxLength(255),
                    Textarea::make('out_of_scope_usage')
                        ->label('Out-of-scope usage')
                        ->rows(2)
                        ->columnSpanFull(),
                    Textarea::make('environmental_impact')
                        ->label('Environmental impact')
                        ->rows(2)
                        ->columnSpanFull(),
                    Textarea::make('vulnerabilities')
                        ->label('Vulnerabilities')
                        ->placeholder('Known CVEs, severity ratings and security advisory references.')
                        ->rows(2)
                        ->columnSpanFull(),
                    Textarea::make('attestations')
                        ->label('Attestations (signature / integrity)')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
            Section::make('Evaluation and notes')
                ->schema([
                    Textarea::make('evaluation')
                        ->label('Evaluation / testing')
                        ->rows(3)
                        ->columnSpanFull(),
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
                ->label('AIBOM')
                ->relationship('bom', 'name', modifyQueryUsing: fn ($query) => $query->where('type', 'aibom'))
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull(),
            ...static::fields(),
        ]);
    }
}

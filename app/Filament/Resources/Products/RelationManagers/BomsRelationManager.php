<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Enums\BomStatus;
use App\Enums\BomType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BomsRelationManager extends RelationManager
{
    protected static string $relationship = 'boms';

    protected static ?string $title = 'Bills of Materials';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options(BomType::class)
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('version')
                    ->required()
                    ->default('1.0')
                    ->maxLength(255),
                TextInput::make('specification')
                    ->maxLength(255),
                Select::make('status')
                    ->options(BomStatus::class)
                    ->default(BomStatus::Draft)
                    ->required(),
                DatePicker::make('released_at'),
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('type')
                    ->color(fn (BomType $state): string => $state->getColor()),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('version')
                    ->sortable(),
                TextColumn::make('status')
                    ->color(fn (BomStatus $state): string => $state->getColor()),
                TextColumn::make('released_at')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['created_by'] ??= auth()->id();

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record): string => route('filament.admin.resources.boms.edit', $record)),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

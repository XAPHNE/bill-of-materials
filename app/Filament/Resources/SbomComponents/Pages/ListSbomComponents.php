<?php

namespace App\Filament\Resources\SbomComponents\Pages;

use App\Filament\Resources\SbomComponents\SbomComponentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSbomComponents extends ListRecords
{
    protected static string $resource = SbomComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

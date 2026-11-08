<?php

namespace App\Filament\Resources\HbomComponents\Pages;

use App\Filament\Resources\HbomComponents\HbomComponentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHbomComponents extends ListRecords
{
    protected static string $resource = HbomComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

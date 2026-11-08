<?php

namespace App\Filament\Resources\CbomAssets\Pages;

use App\Filament\Resources\CbomAssets\CbomAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCbomAssets extends ListRecords
{
    protected static string $resource = CbomAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

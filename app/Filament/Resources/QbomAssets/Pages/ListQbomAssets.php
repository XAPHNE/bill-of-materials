<?php

namespace App\Filament\Resources\QbomAssets\Pages;

use App\Filament\Resources\QbomAssets\QbomAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQbomAssets extends ListRecords
{
    protected static string $resource = QbomAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

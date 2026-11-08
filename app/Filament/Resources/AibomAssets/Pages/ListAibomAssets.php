<?php

namespace App\Filament\Resources\AibomAssets\Pages;

use App\Filament\Resources\AibomAssets\AibomAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAibomAssets extends ListRecords
{
    protected static string $resource = AibomAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

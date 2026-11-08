<?php

namespace App\Filament\Resources\CbomAssets\Pages;

use App\Filament\Resources\CbomAssets\CbomAssetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCbomAsset extends EditRecord
{
    protected static string $resource = CbomAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

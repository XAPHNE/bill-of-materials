<?php

namespace App\Filament\Resources\AibomAssets\Pages;

use App\Filament\Resources\AibomAssets\AibomAssetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAibomAsset extends EditRecord
{
    protected static string $resource = AibomAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

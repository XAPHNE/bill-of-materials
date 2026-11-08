<?php

namespace App\Filament\Resources\QbomAssets\Pages;

use App\Filament\Resources\QbomAssets\QbomAssetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQbomAsset extends EditRecord
{
    protected static string $resource = QbomAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

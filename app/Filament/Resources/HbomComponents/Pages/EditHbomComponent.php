<?php

namespace App\Filament\Resources\HbomComponents\Pages;

use App\Filament\Resources\HbomComponents\HbomComponentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHbomComponent extends EditRecord
{
    protected static string $resource = HbomComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

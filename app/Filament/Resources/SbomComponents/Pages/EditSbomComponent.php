<?php

namespace App\Filament\Resources\SbomComponents\Pages;

use App\Filament\Resources\SbomComponents\SbomComponentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSbomComponent extends EditRecord
{
    protected static string $resource = SbomComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

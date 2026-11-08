<?php

namespace App\Filament\Resources\Boms\Pages;

use App\Filament\Actions\ExportBomXlsxAction;
use App\Filament\Resources\Boms\BomResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBom extends EditRecord
{
    protected static string $resource = BomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportBomXlsxAction::make(),
            DeleteAction::make(),
        ];
    }
}

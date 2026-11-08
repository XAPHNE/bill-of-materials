<?php

namespace App\Filament\Actions;

use App\Exports\BomXlsxExporter;
use App\Models\Bom;
use Filament\Actions\Action;

class ExportBomXlsxAction
{
    public static function make(): Action
    {
        return Action::make('exportXlsx')
            ->label('Export XLSX')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(function (Bom $record) {
                $exporter = new BomXlsxExporter;
                $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'bom-'.uniqid().'.xlsx';

                $exporter->export($record, $path);

                return response()
                    ->download($path, $exporter->suggestedFileName($record), [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->deleteFileAfterSend(true);
            });
    }
}

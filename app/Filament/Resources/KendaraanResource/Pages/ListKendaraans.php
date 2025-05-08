<?php

namespace App\Filament\Resources\KendaraanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\KendaraanResource;
use App\Filament\Imports\DataKendaraanImporter;

class ListKendaraans extends ListRecords
{
    protected static string $resource = KendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // Actions\ImportAction::make('Import Data')
            // ->importer(DataKendaraanImporter::class)
            // ->maxRows(100000)
            // ->chunkSize(1000)
            // ->icon('heroicon-s-arrow-up-tray')
            // ->Button()
            // ->options([
            //     'updateExisting' => true,
            // ]),
        ];
    }
}

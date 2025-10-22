<?php

namespace App\Filament\Resources\AkunMasterResource\Pages;

use App\Filament\Resources\AkunMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAkunMasters extends ListRecords
{
    protected static string $resource = AkunMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
             Actions\ImportAction::make()
            ->importer(\App\Filament\Imports\AkunMasterImporter::class),
        ];
    }
}

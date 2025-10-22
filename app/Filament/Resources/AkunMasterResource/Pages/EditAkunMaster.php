<?php

namespace App\Filament\Resources\AkunMasterResource\Pages;

use App\Filament\Resources\AkunMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAkunMaster extends EditRecord
{
    protected static string $resource = AkunMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

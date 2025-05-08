<?php

namespace App\Filament\Resources\TransaksiUMKResource\Pages;

use App\Filament\Resources\TransaksiUMKResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiUMK extends EditRecord
{
    protected static string $resource = TransaksiUMKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

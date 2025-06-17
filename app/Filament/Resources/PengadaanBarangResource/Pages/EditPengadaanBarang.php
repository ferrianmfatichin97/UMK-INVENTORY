<?php

namespace App\Filament\Resources\PengadaanBarangResource\Pages;

use App\Filament\Resources\PengadaanBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengadaanBarang extends EditRecord
{
    protected static string $resource = PengadaanBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

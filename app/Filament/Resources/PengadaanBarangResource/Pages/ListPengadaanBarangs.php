<?php

namespace App\Filament\Resources\PengadaanBarangResource\Pages;

use App\Filament\Resources\PengadaanBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengadaanBarangs extends ListRecords
{
    protected static string $resource = PengadaanBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

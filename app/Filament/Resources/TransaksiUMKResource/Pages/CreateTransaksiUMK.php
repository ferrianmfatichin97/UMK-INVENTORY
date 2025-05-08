<?php

namespace App\Filament\Resources\TransaksiUMKResource\Pages;

use Filament\Actions;
use App\Models\TransaksiUMK;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TransaksiUMKResource;

class CreateTransaksiUMK extends CreateRecord
{
    protected static string $resource = TransaksiUMKResource::class;
    protected function afterSave(): void
    {
        logger()->info('Transaksi UMK Created:', $this->record->toArray());
    }

}

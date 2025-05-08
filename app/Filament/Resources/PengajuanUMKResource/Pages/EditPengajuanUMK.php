<?php

namespace App\Filament\Resources\PengajuanUMKResource\Pages;

use App\Filament\Resources\PengajuanUMKResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanUMK extends EditRecord
{
    protected static string $resource = PengajuanUMKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

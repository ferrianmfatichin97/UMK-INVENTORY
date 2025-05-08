<?php

namespace App\Filament\Resources\ApprovalUMKResource\Pages;

use App\Filament\Resources\ApprovalUMKResource;
use App\Models\PengajuanUMK;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovalUMK extends EditRecord
{
    protected static string $resource = ApprovalUMKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            // ->mutateRecordDataUsing(function (array $data): array {
            //     $data = PengajuanUMK(['total_pengajuan']);

            //     return $data;
            // })
            ,
        ];
    }
}

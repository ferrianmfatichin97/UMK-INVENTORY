<?php

namespace App\Filament\Resources\ApprovalUMKResource\Pages;

use App\Filament\Resources\ApprovalUMKResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalUMKS extends ListRecords
{
    protected static string $resource = ApprovalUMKResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }
}

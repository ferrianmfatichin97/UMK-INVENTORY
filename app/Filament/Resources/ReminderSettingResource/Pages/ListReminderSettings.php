<?php

namespace App\Filament\Resources\ReminderSettingResource\Pages;

use App\Filament\Resources\ReminderSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReminderSettings extends ListRecords
{
    protected static string $resource = ReminderSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

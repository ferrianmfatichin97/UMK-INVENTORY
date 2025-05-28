<?php

namespace App\Filament\Resources\PengadaanResource\Pages;

use App\Filament\Resources\PengadaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;

class ListPengadaans extends ListRecords
{
    protected static string $resource = PengadaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('file_lampiran')
                ->label('Lihat PDF')
                ->url(fn ($record) => Storage::url($record->document))
                ->openUrlInNewTab()
                ->formatStateUsing(fn ($state) => '📄 Lihat PDF'),
        ];
    }
}

<?php

namespace App\Filament\Resources\PengadaanBarangResource\Pages;

use App\Filament\Resources\PengadaanBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\View;
use Filament\Infolists\Components\Grid;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPengadaanBarang extends ViewRecord
{
    protected static string $resource = PengadaanBarangResource::class;

     public function infolist(Infolist $infolist): Infolist
{
    return $infolist->schema([
        \Filament\Infolists\Components\Section::make('Informasi Umum')
            ->columns(2)
            ->schema([
                TextEntry::make('divisi'),
                TextEntry::make('nota_dinas'),
                TextEntry::make('urgensi'),
                TextEntry::make('tanggal_dibutuhkan')->date(),
                TextEntry::make('note')->columnSpanFull(),
            ]),

        \Filament\Infolists\Components\Section::make('Daftar Barang')
            ->schema([
                RepeatableEntry::make('details')
                    ->schema([
                        TextEntry::make('nama_barang'),
                        TextEntry::make('jumlah'),
                        TextEntry::make('spesifikasi'),
                        TextEntry::make('catatan'),
                        TextEntry::make('link')
                            ->label('Link Toko')
                            ->url(fn($state) => $state)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-link'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]),

        \Filament\Infolists\Components\Section::make('Lampiran')
            ->columns(3)
            ->schema([
                IconEntry::make('lampiran_nodin')
                    ->label('Nota Dinas')
                    ->icon('heroicon-o-document')
                    ->url(fn($record) => $record->lampiran_nodin ? Storage::url($record->lampiran_nodin) : null)
                    ->openUrlInNewTab(),

                IconEntry::make('lampiran_1')
                    ->label('Lampiran 1')
                    ->icon('heroicon-o-document')
                    ->url(fn($record) => $record->lampiran_1 ? Storage::url($record->lampiran_1) : null)
                    ->openUrlInNewTab(),

                IconEntry::make('lampiran_2')
                    ->label('Lampiran 2')
                    ->icon('heroicon-o-document')
                    ->url(fn($record) => $record->lampiran_2 ? Storage::url($record->lampiran_2) : null)
                    ->openUrlInNewTab(),
            ]),
    ]);
}
}



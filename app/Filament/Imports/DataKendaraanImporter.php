<?php

namespace App\Filament\Imports;

use App\Models\Data_kendaraan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DataKendaraanImporter extends Importer
{
    protected static ?string $model = Data_kendaraan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('jenis_kendaraan')
                ->label('Jenis Kendaraan')
                ->rules(['max:255']),
            ImportColumn::make('merk')
                ->label('Merk')
                ->rules(['max:255']),
            ImportColumn::make('type')
                ->label('Type')
                ->rules(['max:255']),
            ImportColumn::make('no_rangka')
                ->label('No Rangka')
                ->rules(['max:255']),
            ImportColumn::make('no_registrasi')
                ->label('No Registrasi')
                ->rules(['max:255']),
            ImportColumn::make('no_bpkb')
                ->label('No BPKB')
                ->rules(['max:255']),
            ImportColumn::make('kantor_cabang')
                ->label('Kantor Cabang')
                ->rules(['max:255']),
            ImportColumn::make('jadwal_pajak')
                ->label('Jadwal Pajak')
                ->rules(['max:255']),
            ImportColumn::make('perusahaan_asuransi')
                ->label('Perusahaan Asuransi')
                ->rules(['max:255']),
            ImportColumn::make('asuransi_mulai')
                ->label('Asuransi Mulai')
                ->rules(['max:255']),
            ImportColumn::make('asuransi_akhir')
                ->label('Asuransi Akhir')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Data_kendaraan
    {
        // return DataKendaraan::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Data_kendaraan();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your data kendaraan import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

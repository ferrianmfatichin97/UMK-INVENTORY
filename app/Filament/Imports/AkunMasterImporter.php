<?php

namespace App\Filament\Imports;

use App\Models\akun_master;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class AkunMasterImporter extends Importer
{
    protected static ?string $model = akun_master::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('akun_bpr')
                ->label('Akun BPR'),

            ImportColumn::make('nama_akun')
                ->label('Nama Akun'),
        ];
    }

    public function resolveRecord(): ?akun_master
    {
        $akunBpr = $this->data['akun_bpr'] ?? null;

        Log::info('[AkunMasterImporter] Resolve Record', [
            'akun_bpr' => $akunBpr,
        ]);

        // Kalau akun_bpr kosong, buat record baru kosong
        if (!$akunBpr) {
            return new akun_master();
        }

        // Coba update jika akun_bpr sudah ada
        return akun_master::firstOrNew([
            'akun_bpr' => $akunBpr,
        ]);
    }

    public function fillRecord(): void
    {
        try {
            $this->record->akun_bpr = $this->data['akun_bpr'] ?? null;
            $this->record->nama_akun = $this->data['nama_akun'] ?? null;

            Log::info('[AkunMasterImporter] Data siap disimpan', [
                'akun_bpr' => $this->record->akun_bpr,
                'nama_akun' => $this->record->nama_akun,
            ]);

            // ✅ Simpan manual karena Filament 3.2+ tidak auto-save setelah fillRecord
            $this->record->save();

            Log::info('[AkunMasterImporter] ✅ Data tersimpan ke DB', [
                'id' => $this->record->id,
            ]);

        } catch (\Throwable $e) {
            Log::error('[AkunMasterImporter] ❌ Gagal menyimpan', [
                'error' => $e->getMessage(),
                'akun_bpr' => $this->data['akun_bpr'] ?? null,
            ]);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        Log::info('[AkunMasterImporter] Import selesai', [
            'success' => $import->successful_rows,
            'failed' => $import->getFailedRowsCount(),
        ]);

        return 'Import selesai. ' . number_format($import->successful_rows) . ' baris berhasil diimpor, '
            . number_format($import->getFailedRowsCount()) . ' gagal.';
    }
}

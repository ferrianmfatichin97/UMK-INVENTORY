<?php
namespace App\Filament\Resources\TransaksiUMKResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengajuanSummaryWidgets extends BaseWidget
{
    protected function getStats(): array
    {
        // Ambil 3 pengajuan terakhir
        $lastThree = DB::table('view_pengajuanumk as p')
            ->select(
                'p.kode_pengajuan',
                DB::raw('MAX(p.tanggal_pengajuan) as tanggal_pengajuan'),
                DB::raw('SUM(p.total_pengajuan) as total_pengajuan')
            )
            ->groupBy('p.kode_pengajuan')
            ->orderByDesc(DB::raw('MAX(p.tanggal_pengajuan)'))
            ->limit(3)
            ->get();

        $stats = [];

        foreach ($lastThree as $pengajuan) {
            // Total transaksi dari tabel transaksiumk
            $totalTransaksi = DB::table('transaksiumk')
                ->where('no_pengajuan', $pengajuan->kode_pengajuan)
                ->sum('nominal');

            // Hitung sisa saldo
            $sisa = $pengajuan->total_pengajuan - $totalTransaksi;

            // Buat 1 kartu per kode_pengajuan
            $stats[] = Stat::make(
                $pengajuan->kode_pengajuan, // Judul kartu (kode pengajuan)
                '💰 Rp ' . number_format($pengajuan->total_pengajuan, 0, ',', '.')
            )
                ->description(
                    "📝 Transaksi: Rp " . number_format($totalTransaksi, 0, ',', '.') . "\n" .
                    "📌 Sisa: Rp " . number_format($sisa, 0, ',', '.') . "\n" .
                    "📅 Tgl: " . Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y')
                )
                ->color($sisa > 0 ? 'warning' : 'success');
        }

        return $stats;
    }
}

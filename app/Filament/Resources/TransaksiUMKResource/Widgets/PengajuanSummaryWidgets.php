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
        $stats = [];
        $totalPengajuan = 10000000;


        $lastThree = DB::table('transaksiumk')
            ->select('no_pengajuan', DB::raw('MAX(tanggal) as tanggal'))
            ->groupBy('no_pengajuan')
            ->orderByDesc(DB::raw('MAX(tanggal)'))
            ->limit(3)
            ->get();

        foreach ($lastThree as $trx) {
            $totalTransaksi = DB::table('transaksiumk')
                ->where('no_pengajuan', $trx->no_pengajuan)
                ->sum('nominal');

            $sisa = $totalPengajuan - $totalTransaksi;

            $stats[] = Stat::make(
                $trx->no_pengajuan,
                '💰 Rp ' . number_format($totalPengajuan, 0, ',', '.')
            )
                ->description(
                    "Transaksi: Rp " . number_format($totalTransaksi, 0, ',', '.') . "\n" .
                    "Sisa: Rp " . number_format($sisa, 0, ',', '.') . "\n"

                )
                ->color($sisa <= 0 ? 'success' : 'warning');
        }

        return $stats;
    }
}

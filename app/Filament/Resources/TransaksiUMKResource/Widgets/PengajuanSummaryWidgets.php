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
            $totalTransaksi = DB::table('transaksiumk')
                ->where('no_pengajuan', $pengajuan->kode_pengajuan)
                ->sum('nominal');

            $sisa = $pengajuan->total_pengajuan - $totalTransaksi;

            $stats[] = Stat::make(
                $pengajuan->kode_pengajuan,
                'Transaksi: Rp ' . number_format($totalTransaksi, 0, ',', '.')
            )
                ->description(
                    'Sisa saldo: Rp ' . number_format($sisa, 0, ',', '.') .
                    ' | Total pengajuan: Rp ' . number_format($pengajuan->total_pengajuan, 0, ',', '.') .
                    ' | Tgl: ' . Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y')
                )
                ->color($sisa > 0 ? 'warning' : 'success');
        }

        return $stats;
    }
}

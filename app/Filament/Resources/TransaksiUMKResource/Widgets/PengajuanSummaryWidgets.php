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
        $lastThree = DB::table('transaksiumk')
            ->select('no_pengajuan', DB::raw('SUM(nominal) as total'), DB::raw('MAX(tanggal) as tanggal'))
            ->groupBy('no_pengajuan')
            ->orderByDesc(DB::raw('MAX(tanggal)'))
            ->limit(3)
            ->get();

        $stats = [];

        foreach ($lastThree as $trx) {
            $stats[] = Stat::make(
                $trx->no_pengajuan,
                'Rp ' . number_format($trx->total, 0, ',', '.')
            )
                ->description('Tanggal: ' . Carbon::parse($trx->tanggal)->translatedFormat('d F Y'))
                ->color('success');
        }

        return $stats;
    }
}

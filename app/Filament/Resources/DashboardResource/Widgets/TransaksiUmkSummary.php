<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\TransaksiUMK;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TransaksiUmkSummary extends BaseWidget
{
    protected function getCards(): array
    {
        $totalTransaksi = TransaksiUMK::count();
        $totalNominal = TransaksiUMK::sum('nominal');
        $transaksiHariIni = TransaksiUMK::whereDate('tanggal', Carbon::today())->count();
        $totalQty = TransaksiUMK::sum('qty');

        $topNamaAkun = TransaksiUMK::selectRaw('nama_akun, COUNT(*) as total')
            ->groupBy('nama_akun')
            ->orderByDesc('total')
            ->first();

        return [
            Stat::make('Total Transaksi', $totalTransaksi)
                ->description('Jumlah seluruh transaksi UMK')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary'),

            Stat::make('Total Nominal', 'Rp ' . number_format($totalNominal, 0, ',', '.'))
                ->description('Akumulasi seluruh nominal transaksi')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Transaksi Hari Ini', $transaksiHariIni)
                ->description('Transaksi yang terjadi hari ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            // Stat::make('Nama Akun Terbanyak', $topNamaAkun?->nama_akun ?? '-')
            //     ->description("Digunakan " . ($topNamaAkun?->total ?? 0) . " kali")
            //     ->descriptionIcon('heroicon-m-user-circle')
            //     ->color('teal'),
        ];
    }
}

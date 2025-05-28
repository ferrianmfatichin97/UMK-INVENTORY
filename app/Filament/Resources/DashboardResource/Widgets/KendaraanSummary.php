<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\Data_kendaraan;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KendaraanSummary extends BaseWidget
{
    protected function getCards(): array
    {
        $totalKendaraan = Data_kendaraan::count();

        $kendaraanMotor = Data_kendaraan::where('jenis_kendaraan', 'motor')->count();
        $kendaraanMobil = Data_kendaraan::where('jenis_kendaraan', 'mobil')->count();

        $asuransiAkanHabis = Data_kendaraan::whereDate('asuransi_akhir', '<=', Carbon::now()->addDays(30))->count();
        $pajakAkanJatuhTempo = Data_kendaraan::whereDate('jadwal_pajak', '<=', Carbon::now()->addDays(30))->count();

        return [
            Stat::make('Total Kendaraan', $totalKendaraan)
                ->description('Jumlah kendaraan terdaftar')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),

            Stat::make('Motor', $kendaraanMotor)
                ->description('Jumlah kendaraan motor')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('success'),

            Stat::make('Mobil', $kendaraanMobil)
                ->description('Jumlah kendaraan mobil')
                //->descriptionIcon('heroicon-m-car')
                ->color('info'),

            Stat::make('Asuransi Habis < 30 Hari', $asuransiAkanHabis)
                ->description('Perlu diperbarui segera')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color('warning'),

            Stat::make('Pajak Jatuh Tempo < 30 Hari', $pajakAkanJatuhTempo)
                ->description('Segera bayar pajak')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('danger'),
        ];
    }
}

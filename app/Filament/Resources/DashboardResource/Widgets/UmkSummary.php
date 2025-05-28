<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\PengajuanUMK;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UmkSummary extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Total Pengajuan', PengajuanUMK::count())
                ->description('Jumlah pengajuan UMK')
                ->descriptionIcon('heroicon-m-document')
                ->color('primary'),

            Stat::make('Total Nominal Pengajuan', 'Rp ' . number_format(PengajuanUMK::sum('total_pengajuan'), 0, ',', '.'))
                ->description('Total seluruh Pengajuan UMK')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}

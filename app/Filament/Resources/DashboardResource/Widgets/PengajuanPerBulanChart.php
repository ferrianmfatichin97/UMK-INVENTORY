<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\PengajuanUMK;
use Filament\Widgets\ChartWidget;

class PengajuanPerBulanChart extends ChartWidget
{
    
    protected function getData(): array
    {
        $data = PengajuanUMK::selectRaw("MONTH(tanggal_pengajuan) as bulan, SUM(total_pengajuan) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $labels = $data->keys()->map(function ($bulan) use ($namaBulan) {
            return $namaBulan[(int)$bulan];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengajuan',
                    'data' => $data->values(),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected static ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'line';
    }
}

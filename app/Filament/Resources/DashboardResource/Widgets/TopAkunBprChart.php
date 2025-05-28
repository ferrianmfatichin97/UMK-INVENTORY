<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\TransaksiUMK;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopAkunBprChart extends ChartWidget
{
    protected static ?string $heading = 'Pengajuan Akun BPR Terbanyak';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = TransaksiUMK::select('nama_akun', DB::raw('SUM(nominal) as total_nominal'))
            ->groupBy('nama_akun')
            ->orderByDesc('total_nominal')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Nominal (Rp)',
                    'data' => $data->pluck('total_nominal'),
                    'backgroundColor' => [
                        '#4f46e5',
                        '#22c55e',
                        '#ec4899',
                        '#f59e0b',
                        '#10b981',
                    ],
                ],
            ],
            'labels' => $data->pluck('nama_akun'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

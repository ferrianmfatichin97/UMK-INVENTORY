<?php

namespace App\Filament\Resources\TransaksiUMKResource\Pages;

use App\Models\pengajuan_detail;
use Carbon\Carbon;
use Filament\Actions;
use App\Models\PengajuanUMK;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Config;
use Filament\Resources\Pages\ListRecords;
use Riskihajar\Terbilang\Facades\Terbilang;
use App\Filament\Resources\TransaksiUMKResource;
use App\Models\TransaksiUMK;

class ListTransaksiUMKS extends ListRecords
{
    protected static string $resource = TransaksiUMKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Download Laporan')
                ->form([
                    Select::make('nomor_pengajuan')
                        ->label('Pengajuan')
                        ->options(PengajuanUMK::query()->pluck('nomor_pengajuan', 'nomor_pengajuan'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $nomor_pengajuan = $data['nomor_pengajuan'];
                    $formattedTanggal = DB::table('pengajuanumk')
                        ->where('nomor_pengajuan', $nomor_pengajuan)
                        ->value('tanggal_pengajuan');

                    $tanggal = Carbon::parse($formattedTanggal)->translatedFormat('d F Y');

                    $pengajuan = pengajuan_detail::select(
                        'kode_akun AS A',
                        'nama_akun AS B',
                        'nomor_pengajuan AS C',
                        DB::raw('SUM(jumlah) AS D'),
                        DB::raw("'Pengajuan' AS Sumber")
                    )
                        ->where('nomor_pengajuan', $nomor_pengajuan)
                        ->groupBy('kode_akun', 'nama_akun', 'nomor_pengajuan');
                    $transaksi = Transaksiumk::select(
                        'akun_bpr AS A',
                        'nama_akun AS B',
                        'no_pengajuan AS C',
                        DB::raw('SUM(nominal) AS D'),
                        DB::raw("'Transaksi' AS Sumber")
                    )
                        ->where('no_pengajuan', $nomor_pengajuan)
                        ->groupBy('akun_bpr', 'nama_akun', 'no_pengajuan');

                    $data = $pengajuan->unionAll($transaksi)->get();

                    $pivoted = [];
                    foreach ($data as $row) {
                        $key = $row->C . '|' . $row->A;
                        if (!isset($pivoted[$key])) {
                            $pivoted[$key] = [
                                'C' => $row->C,
                                'A' => $row->A,
                                'B' => $row->B,
                                'Transaksi' => 0,
                                'Pengajuan' => 0,
                            ];
                        }
                        $pivoted[$key][$row->Sumber] = $row->D;
                    }
                    $detail = array_values($pivoted);
                    //dd($detail);
                    $totalpengajuan = collect($detail)->sum('Pengajuan'); 
                    $totalrealisasi = collect($detail)->sum('Transaksi');
                    $totalselisih = $totalpengajuan - $totalrealisasi;

                    Config::set('terbilang.locale', 'id');
                    $terbilang = Terbilang::make($totalselisih, ' rupiah');

                    $user = Auth::user();
                    $userName = $user ? $user->name : 'Unknown User';

                    $path = 'logo.jpg';
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                    $filename = "Laporan UMK_$nomor_pengajuan.pdf";

                    return response()->stream(function () use ($detail, $base64, $tanggal, $totalselisih, $totalrealisasi, $nomor_pengajuan, $totalpengajuan, $userName, $terbilang) {
                        echo Pdf::loadView('LPJWB', [
                            'image' => $base64,
                            'transaksis' => $detail,
                            'userName' => $userName,
                            'tanggal' => $tanggal,
                            'nomor' => $nomor_pengajuan,
                            'total_pengajuan' => $totalpengajuan,
                            'total_realisasi' => $totalrealisasi,
                            'total_selisih' => $totalselisih,
                            'terbilang' => $terbilang,
                        ])->output();
                    }, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                    ]);
                })
        ];
    }
}

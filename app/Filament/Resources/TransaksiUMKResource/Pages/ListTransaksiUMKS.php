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
                    $nomor_pengajuan_detail = $data['nomor_pengajuan'];
                    
                    $pengajuan = DB::table('pengajuanumk')
                    ->where('nomor_pengajuan', $nomor_pengajuan_detail)
                    ->get();

                    $tanggal_pengajuan = $pengajuan->first()->tanggal_pengajuan;
                    $tanggal = Carbon::parse($tanggal_pengajuan)->translatedFormat('d F Y');

                    //dd($pengajuan);

                    $detail = DB::table('transaksiumk as pd')
                        ->select(
                            DB::raw('ROW_NUMBER() OVER (ORDER BY pd.akun_bpr) AS No'),
                            'pd.akun_bpr AS kode_akun',
                            'pd.nama_akun AS nama_akun',
                            DB::raw('COALESCE(t.jumlah, 0) AS pengajuan'),
                            DB::raw('COALESCE(SUM(pd.nominal), 0) AS realisasi'),
                            DB::raw('(COALESCE(t.jumlah, 0) - COALESCE(SUM(pd.nominal), 0)) AS selisih')
                        )
                        ->leftJoin('pengajuan_details as t', function ($join) {
                            $join->on('pd.akun_bpr', '=', 't.kode_akun')
                                ->on(DB::raw('SUBSTRING(pd.no_pengajuan, 12, 5)'), '=', 't.nomor_pengajuan');
                        })
                        ->where('pd.no_pengajuan', $nomor_pengajuan_detail)
                        ->groupBy('pd.akun_bpr', 'pd.nama_akun', 't.jumlah')
                        ->get();

                    $totalpengajuan = $detail->sum('pengajuan');
                    $totalrealisasi = $detail->sum('realisasi');
                    $totalselisih = $detail->sum('selisih');

                    Config::set('terbilang.locale', 'id');
                    $terbilang = Terbilang::make($totalselisih, ' rupiah');

                    $user = Auth::user();
                    $userName = $user ? $user->name : 'Unknown User';

                    $path = 'logo.jpg';
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                    $filename = "Laporan UMK_$nomor_pengajuan_detail.pdf";

                    return response()->stream(function () use ($detail, $base64,$totalselisih, $tanggal, $totalrealisasi, $nomor_pengajuan_detail, $totalpengajuan, $userName, $terbilang) {
                        echo Pdf::loadView('LPJWB', [
                            'image' => $base64,
                            'transaksis' => $detail,
                            'userName' => $userName,
                            'tanggal' => $tanggal,
                            'nomor' => $nomor_pengajuan_detail,
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

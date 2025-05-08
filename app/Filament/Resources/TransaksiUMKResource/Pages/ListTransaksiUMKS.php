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
                        ->required(),
                ])
                ->action(function (array $data) {
                    $nomor_pengajuan_detail = $data['nomor_pengajuan'];
                    //$pengajuan = PengajuanUMK::where('nomor_pengajuan', 'LIKE', "%{$nomor_pengajuan_detail}%")->first();

                    preg_match('/-(\d+)\//', $nomor_pengajuan_detail, $matches);
                    $angka = isset($matches[1]) ? $matches[1] : null;
                    $angka_signifikan = ltrim($angka, '0');

                    $angka_signifikan = $angka_signifikan === '' ? '0' : $angka_signifikan;

                    $pengajuan = pengajuan_detail::select('kode_akun', 'nama_akun', 'jumlah', 'keterangan', 'created_at')
                        ->where('nomor_pengajuan', $angka_signifikan)
                        ->get();

                    if (!$pengajuan) {
                        return response()->json(['message' => 'Pengajuan tidak ditemukan'], 404);
                    }

                    $detail = TransaksiUMK::select('akun_bpr', 'nama_akun')
                        ->selectRaw('SUM(CAST(nominal AS DECIMAL(15, 2))) AS total_nominal')
                        ->where('no_pengajuan', $nomor_pengajuan_detail)
                        ->groupBy('akun_bpr', 'nama_akun')
                        ->get();

                    // dd([
                    //     'detail' => $detail,
                    //     'angka' => $angka,
                    //     'pengajuan' => $pengajuan,
                    //     'nomor_pengajuan' => $nomor_pengajuan_detail,
                    // ]);

                    $totalNominal = $detail->sum('nominal');
                    $totalpengajuan = $pengajuan->sum('jumlah');
                    $selisih = $totalpengajuan - $totalNominal;

                    Config::set('terbilang.locale', 'id');
                    $terbilang = Terbilang::make($selisih, ' rupiah');

                    $user = Auth::user();
                    $userName = $user ? $user->name : 'Unknown User';

                    $path = 'logo.jpg';
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                    //$tanggal = $pengajuan->created_at;
                    $tanggal = null;

                    if ($pengajuan->isNotEmpty()) {
                        $firstItem = $pengajuan->first();

                        $tanggal = $firstItem->created_at;
                    }

                    if ($tanggal) {
                        Carbon::setLocale('id');
                        $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $tanggal);
                        $formattedDate = $carbonDate->translatedFormat('d F Y');

                    } else {
                        $formattedDate = null;
                    }

                    $filename = "Laporan UMK_$nomor_pengajuan_detail.pdf";

                    dd([
                        'transaksis' => $detail,
                        'userName' => $userName,
                        'nomor' => $nomor_pengajuan_detail,
                        'terbilang' => $terbilang,
                        'tanggal' => $formattedDate,
                        'nomor_pengajuan' => $nomor_pengajuan_detail,
                        'pengajuan' => $pengajuan,
                        'detail' => $detail,
                        'total_nominal' => $totalNominal,
                        'total_pengajuan' => $totalpengajuan,
                        'selisih' => $selisih
                    ]);

                    return response()->stream(function () use ($detail, $base64, $formattedDate, $totalNominal, $nomor_pengajuan_detail, $totalpengajuan, $userName, $terbilang, $selisih) {
                        echo Pdf::loadView('LPJWB', [
                            'image' => $base64,
                            'transaksis' => $detail,
                            'userName' => $userName,
                            'tanggal' => $formattedDate,
                            'nomor' => $nomor_pengajuan_detail,
                            'total_pengajuan' => $totalpengajuan,
                            'total_nominal' => $totalNominal,
                            'terbilang' => $terbilang,
                            'selisih' => $selisih,
                        ])->output();
                    }, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                    ]);
                })
        ];
    }
}

<?php

namespace App\Filament\Resources\TransaksiUMKResource\Pages;


use App\Filament\Resources\TransaksiUMKResource;
use App\Filament\Resources\TransaksiUMKResource\Widgets\PengajuanSummaryWidgets;
use App\Models\pengajuan_detail;
use App\Models\PengajuanUMK;
use App\Models\TransaksiUMK;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Riskihajar\Terbilang\Facades\Terbilang;

class ListTransaksiUMKS extends ListRecords
{
    protected static string $resource = TransaksiUMKResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PengajuanSummaryWidgets::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Download Laporan')
                ->form([
                    Select::make('jenis_laporan')
                        ->label('Jenis Laporan')
                        ->options([
                            'laporan_1' => 'Laporan PertanggungJawaban',
                            'laporan_2' => 'Laporan Transaksi UMK',
                            'laporan_3' => 'Laporan Rekap Transaksi Per COA',
                            'laporan_4' => 'Laporan Rekap ALL',
                        ])
                        ->required(),
                    Select::make('nomor_pengajuan')
                        ->label('Pengajuan')
                        ->options(PengajuanUMK::query()->orderByDesc('tanggal_pengajuan')->pluck('nomor_pengajuan', 'nomor_pengajuan'))
                        ->searchable()
                        ->required(),

                ])
                ->action(function (array $data) {
                    $nomor_pengajuan = $data['nomor_pengajuan'];
                    $jenis_laporan = $data['jenis_laporan'];
                    $jenisNamaFile = match ($jenis_laporan) {
                        'laporan_1' => 'Laporan_PertanggungJawaban',
                        'laporan_2' => 'Laporan_TransaksiUMK',
                        'laporan_3' => 'Laporan_RekapTransaksiPerCOA',
                        'laporan_4' => 'Laporan_RekapALLTransaksi',
                        default => 'Laporan_UMK',
                    };

                    $filename = "{$jenisNamaFile}_{$nomor_pengajuan}.pdf";
                    // $filename = "Laporan UMK_{$nomor_pengajuan}.pdf";

                    return response()->stream(function () use ($nomor_pengajuan, $jenis_laporan) {
                        switch ($jenis_laporan) {
                            case 'laporan_1':
                                echo $this->generateLaporan1($nomor_pengajuan);
                                break;
                            case 'laporan_2':
                                echo $this->generateLaporan2($nomor_pengajuan);
                                break;
                            case 'laporan_3':
                                echo $this->generateLaporan3($nomor_pengajuan);
                                break;
                            case 'laporan_4':
                                echo $this->generateLaporan4($nomor_pengajuan);
                                break;
                            default:
                                abort(400, 'Jenis laporan tidak dikenal.');
                        }
                    }, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                    ]);
                }),
        ];
    }

    protected function generateLaporan1($nomor_pengajuan)
    {
        // Ambil tanggal pengajuan dari view_pengajuanumk
        $formattedTanggal = DB::table('view_pengajuanumk')
            ->where('kode_pengajuan', $nomor_pengajuan)
            ->value('tanggal_pengajuan');

        $tanggal = Carbon::parse($formattedTanggal)->translatedFormat('d F Y');

        // Ambil data pengajuan dari view_pengajuanumk
        $pengajuan = DB::table('view_pengajuanumk')
            ->select(
                'kode_akun AS A',
                'nama_akun AS B',
                'kode_pengajuan AS C',
                DB::raw('SUM(jumlah) AS D'),
                DB::raw("'Pengajuan' AS Sumber")
            )
            ->where('kode_pengajuan', $nomor_pengajuan)
            ->groupBy('kode_akun', 'nama_akun', 'kode_pengajuan');



        $transaksi = DB::table('transaksiumk')
            ->select(
                'akun_bpr AS A',
                'nama_akun AS B',
                'no_pengajuan AS C',
                DB::raw('SUM(nominal) AS D'),
                DB::raw("'Transaksi' AS Sumber")
            )
            ->where('no_pengajuan', $nomor_pengajuan)
            ->groupBy('akun_bpr', 'nama_akun', 'no_pengajuan');


        // Union kedua data
        $dataGabungan = $pengajuan->unionAll($transaksi)->get();

        // dd([
        //     'nomor_pengajuan' => $nomor_pengajuan,
        //     'pengajuan' => $pengajuan,
        //     'transaksi' => $transaksi,
        //     'datagabungan' => $dataGabungan,
        // ]);

        // Pivot data
        $pivoted = [];
        foreach ($dataGabungan as $row) {
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

        // Group by kode akun + nama akun
        $grouped = [];
        foreach ($detail as $row) {
            $key = $row['A'] . '|' . $row['B'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'C' => $row['C'],
                    'A' => $row['A'],
                    'B' => $row['B'],
                    'Pengajuan' => 0,
                    'Transaksi' => 0,
                ];
            }
            $grouped[$key]['Pengajuan'] += $row['Pengajuan'];
            $grouped[$key]['Transaksi'] += $row['Transaksi'];
        }

        $finalDetail = array_values($grouped);

        // Hitung total
        $totalpengajuan = collect($finalDetail)->sum('Pengajuan');
        $totalrealisasi = collect($finalDetail)->sum('Transaksi');
        $totalselisih = $totalpengajuan - $totalrealisasi;

        // Konversi terbilang
        Config::set('terbilang.locale', 'id');
        $terbilang = Terbilang::make($totalselisih, ' rupiah');

        // Ambil user
        $user = Auth::user();
        $userName = $user ? $user->name : 'Unknown User';

        // Encode logo ke base64
        $path = 'logo.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $imageData = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);

        // Logging
        Log::info('Download Laporan PertanggungJawaban ' . $nomor_pengajuan . ' Oleh: ' . $userName);

        // dd([
        //     'image' => $base64,
        //     'transaksis' => $finalDetail,
        //     'userName' => $userName,
        //     'tanggal' => $tanggal,
        //     'nomor' => $nomor_pengajuan,
        //     'total_pengajuan' => $totalpengajuan,
        //     'total_realisasi' => $totalrealisasi,
        //     'total_selisih' => $totalselisih,
        //     'terbilang' => $terbilang,
        // ]);

        // Return PDF
        return Pdf::loadView('LPJWB', [
            'image' => $base64,
            'transaksis' => $finalDetail,
            'userName' => $userName,
            'tanggal_pengajuan' => $tanggal,
            'nomor' => $nomor_pengajuan,
            'total_pengajuan' => $totalpengajuan,
            'total_realisasi' => $totalrealisasi,
            'total_selisih' => $totalselisih,
            'terbilang' => $terbilang,
            'tanggal_cetak' => Carbon::now()->translatedFormat('d F Y'),
        ])->output();
    }

    protected function generateLaporan2($nomor_pengajuan)
    {
        $formattedTanggal = DB::table('pengajuanumk')
            ->where('nomor_pengajuan', $nomor_pengajuan)
            ->value('tanggal_pengajuan');

        $tanggal = Carbon::parse($formattedTanggal)->translatedFormat('d F Y');

        $details = DB::table('transaksiumk')
            ->select(
                DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(no_pengajuan, '-', -1), '/', 1) AS NO_UMK"),
                'akun_bpr AS AKUN_BPR',
                DB::raw('ROW_NUMBER() OVER (ORDER BY id) AS NO_URUT'),
                'nama_akun AS NAMA_AKUN',
                'keterangan AS KETERANGAN',
                'nominal AS TOTAL'
            )
            ->where('no_pengajuan', $nomor_pengajuan)
            ->get();


        $total = $details->sum('TOTAL');
        Config::set('terbilang.locale', 'id');
        $terbilang = Terbilang::make($total, ' rupiah');
        $user = Auth::user();
        $userName = $user ? $user->name : 'Unknown User';
        $path = 'logo.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $imageData = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
        Log::info('Download Laporan Transaksi UMK ' . $nomor_pengajuan . ' Oleh: ' . $userName);

        // dd([
        //     'image' => $base64,
        //     'details' => $details,
        //     'total' => $total,
        //     'terbilang' => $terbilang,
        //     'userName' => $userName,
        //     'tanggal' => $tanggal,
        //     'nomor' => $nomor_pengajuan,
        // ]);

        return Pdf::loadView('laporantransaksiumk', [
            'image' => $base64,
            'details' => $details,
            'total' => $total,
            'terbilang' => $terbilang,
            'userName' => $userName,
            'tanggal' => $tanggal,
            'nomor' => $nomor_pengajuan,
        ])->output();
    }


    protected function generateLaporan3($nomor_pengajuan)
    {
        $formattedTanggal = DB::table('pengajuanumk')
            ->where('nomor_pengajuan', $nomor_pengajuan)
            ->value('tanggal_pengajuan');

        $tanggal = Carbon::parse($formattedTanggal)->translatedFormat('d F Y');

        $details = DB::table('transaksiumk')
            ->select(
                'no_pengajuan AS NO_UMK',
                'akun_bpr AS AKUN_BPR',
                DB::raw('ROW_NUMBER() OVER (ORDER BY akun_bpr) AS NO_URUT'),
                'nama_akun AS NAMA_AKUN',
                DB::raw('GROUP_CONCAT(keterangan SEPARATOR ", ") AS KETERANGAN'),
                DB::raw('SUM(nominal) AS TOTAL')
            )
            ->where('no_pengajuan', $nomor_pengajuan)
            ->groupBy('no_pengajuan', 'akun_bpr', 'nama_akun')
            ->orderBy('akun_bpr')
            ->orderBy('nama_akun')
            ->get();

        $grandTotal = $details->sum('TOTAL');

        Config::set('terbilang.locale', 'id');
        $terbilang = Terbilang::make($grandTotal, ' rupiah');

        $user = Auth::user();
        $userName = $user ? $user->name : 'Unknown User';

        $path = 'logo.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $imageData = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
        Log::info('Download Laporan Rekap Transaksi Per COA for ' . $nomor_pengajuan . ' Oleh: ' . $userName);
        return Pdf::loadView('laporanrekaptransaksipercoa', [
            'image' => $base64,
            'details' => $details,
            'grandTotal' => $grandTotal,
            'terbilang' => $terbilang,
            'userName' => $userName,
            'tanggal' => $tanggal,
            'nomor' => $nomor_pengajuan,
        ])->output();
    }


    protected function generateLaporan4($nomor_pengajuan)
    {
        $formattedTanggal = DB::table('pengajuanumk')
            ->where('nomor_pengajuan', $nomor_pengajuan)
            ->value('tanggal_pengajuan');

        $tanggal = Carbon::parse($formattedTanggal)->translatedFormat('d F Y');
        $details = DB::table('transaksiumk')
            ->select(
                'no_pengajuan AS NO_UMK',
                'akun_bpr AS AKUN_BPR',
                DB::raw('ROW_NUMBER() OVER (PARTITION BY akun_bpr ORDER BY nama_akun) AS NO_URUT'),
                'nama_akun AS NAMA_AKUN',
                'keterangan AS KETERANGAN',
                'nominal AS TOTAL'
            )
            ->where('no_pengajuan', $nomor_pengajuan)
            ->orderBy('akun_bpr')
            ->orderBy('nama_akun')
            ->get();



        $total = $details->sum('TOTAL');

        Config::set('terbilang.locale', 'id');
        $terbilang = Terbilang::make($total, ' rupiah');

        $user = Auth::user();
        $userName = $user ? $user->name : 'Unknown User';

        $path = 'logo.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $imageData = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
        Log::info('Download Laporan Rekap ALL Transaksi UMK ' . $nomor_pengajuan . ' Oleh: ' . $userName);

        // dd([
        //     'image' => $base64,
        //     'details' => $details,
        //     'total' => $total,
        //     'terbilang' => $terbilang,
        //     'userName' => $userName,
        //     'tanggal' => $tanggal,
        //     'nomor' => $nomor_pengajuan,
        // ]);

        return Pdf::loadView('laporanrekapalltransaksiumk', [
            'image' => $base64,
            'details' => $details,
            'total' => $total,
            'terbilang' => $terbilang,
            'userName' => $userName,
            'tanggal' => $tanggal,
            'nomor' => $nomor_pengajuan,
        ])->output();
    }
}

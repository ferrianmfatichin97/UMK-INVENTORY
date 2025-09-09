<?php

namespace App\Filament\Resources\TransaksiUMKResource\Pages;

use App\Filament\Resources\TransaksiUMKResource;
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
                            'laporan_3' => 'Laporan Rekap Transaksi UMK',
                            'laporan_4' => 'Laporan 4',
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
                        'laporan_3' => 'Laporan_RekapTransaksiUMK',
                        'laporan_4' => 'Laporan_4',
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
        $formattedTanggal = DB::table('pengajuanumk')
            ->where('nomor_pengajuan', $nomor_pengajuan)
            ->value('tanggal_pengajuan');

        $tanggal = Carbon::parse($formattedTanggal)->translatedFormat('d F Y');

        if (preg_match('/000(\d+)/', $nomor_pengajuan, $matches)) {
            $angka = $matches[1];
        } else {
            $angka = null;
        }

        $pengajuan = pengajuan_detail::select(
            'kode_akun AS A',
            'nama_akun AS B',
            'nomor_pengajuan AS C',
            DB::raw('SUM(jumlah) AS D'),
            DB::raw("'Pengajuan' AS Sumber")
        )
            ->where('nomor_pengajuan', $angka)
            ->groupBy('kode_akun', 'nama_akun', 'nomor_pengajuan');

        $transaksi = TransaksiUMK::select(
            'akun_bpr AS A',
            'nama_akun AS B',
            'no_pengajuan AS C',
            DB::raw('SUM(nominal) AS D'),
            DB::raw("'Transaksi' AS Sumber")
        )
            ->where('no_pengajuan', $nomor_pengajuan)
            ->groupBy('akun_bpr', 'nama_akun', 'no_pengajuan');

        $dataGabungan = $pengajuan->unionAll($transaksi)->get();

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

        $totalpengajuan = collect($finalDetail)->sum('Pengajuan');
        $totalrealisasi = collect($finalDetail)->sum('Transaksi');
        $totalselisih = $totalpengajuan - $totalrealisasi;

        Config::set('terbilang.locale', 'id');
        $terbilang = Terbilang::make($totalselisih, ' rupiah');

        $user = Auth::user();
        $userName = $user ? $user->name : 'Unknown User';

        $path = 'logo.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $imageData = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
        Log::info('Download Laporan PertanggungJawaban for ' . $nomor_pengajuan.' Oleh: '.$userName);
        
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

        return Pdf::loadView('LPJWB', [
            'image' => $base64,
            'transaksis' => $finalDetail,
            'userName' => $userName,
            'tanggal' => $tanggal,
            'nomor' => $nomor_pengajuan,
            'total_pengajuan' => $totalpengajuan,
            'total_realisasi' => $totalrealisasi,
            'total_selisih' => $totalselisih,
            'terbilang' => $terbilang,
        ])->output();
    }

    protected function generateLaporan2($nomor_pengajuan)
    {
        $formattedTanggal = DB::table('pengajuanumk')
            ->where('nomor_pengajuan', $nomor_pengajuan)
            ->value('tanggal_pengajuan');

        $tanggal = Carbon::parse($formattedTanggal)->translatedFormat('d F Y');
        Log::info('Download Laporan Transaksi UMK for ' . $nomor_pengajuan);

        $details = DB::table('view_pengajuanumk')
            ->select(
                'kode_pengajuan AS NO_UMK',
                'kode_akun AS AKUN_BPR',
                DB::raw('ROW_NUMBER() OVER (ORDER BY kode_akun) AS NO_URUT'),
                'nama_akun AS NAMA_AKUN',
                'keterangan_detail AS KETERANGAN',
                'jumlah AS JUMLAH'
            )
            ->where('kode_pengajuan', $nomor_pengajuan)
            ->orderBy('kode_akun')
            ->orderBy('nama_akun')
            ->get();

        $total = $details->sum('JUMLAH');

        Config::set('terbilang.locale', 'id');
        $terbilang = Terbilang::make($total, ' rupiah');

        $user = Auth::user();
        $userName = $user ? $user->name : 'Unknown User';

        $path = 'logo.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $imageData = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
        Log::info('Download Laporan Transaksi UMK for ' . $nomor_pengajuan.' Oleh: '.$userName);
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
                'nama_akun AS NAMA_AKUN',
                DB::raw('SUM(nominal) AS TOTAL')
            )
            ->where('no_pengajuan', $nomor_pengajuan)
            ->groupBy('no_pengajuan', 'akun_bpr', 'nama_akun')
            ->orderBy('akun_bpr')
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
        Log::info('Download Laporan Rekap Transaksi UMK for ' . $nomor_pengajuan.' Oleh: '.$userName);
        return Pdf::loadView('laporanrekaptransaksiumk', [
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
        return '';
    }
}

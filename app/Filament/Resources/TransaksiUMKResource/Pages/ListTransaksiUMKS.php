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
                    Select::make('jenis_laporan')
                        ->label('Jenis Laporan')
                        ->options([
                            'laporan_1' => 'Laporan PertanggungJawaban',
                            'laporan_2' => 'Laporan Transaksi UMK',
                            'laporan_3' => 'Laporan 3',
                            'laporan_4' => 'Laporan 4',
                        ])
                        ->required(),
                    Select::make('nomor_pengajuan')
                        ->label('Pengajuan')
                        ->options(PengajuanUMK::query()->pluck('nomor_pengajuan', 'nomor_pengajuan'))
                        ->searchable()
                        ->required(),
                    
                ])
                ->action(function (array $data) {
                    $nomor_pengajuan = $data['nomor_pengajuan'];
                    $jenis_laporan = $data['jenis_laporan'];
                    $filename = "Laporan UMK_{$nomor_pengajuan}.pdf";

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

        $detail = DB::table('pengajuan_details')
            ->select(
                'nomor_pengajuan',
                'kode_akun',
                'nama_akun',
                'keterangan',
                'jumlah'
            )
            ->where('nomor_pengajuan', $nomor_pengajuan)
            ->orderBy('kode_akun')
            ->get();

        dd([
            'details' => $detail,
            'tanggal' => $tanggal,
            'nomor' => $nomor_pengajuan,
        ]);

        $total = $detail->sum('jumlah');

        Config::set('terbilang.locale', 'id');
        $terbilang = Terbilang::make($total, ' rupiah');

        $user = Auth::user();
        $userName = $user ? $user->name : 'Unknown User';

        $path = 'logo.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $imageData = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);

        return Pdf::loadView('LPJWB_2', [
            'image' => $base64,
            'details' => $detail,
            'total' => $total,
            'terbilang' => $terbilang,
            'userName' => $userName,
            'tanggal' => $tanggal,
            'nomor' => $nomor_pengajuan,
        ])->output();
    }

    protected function generateLaporan3($nomor_pengajuan)
    {
        return '';
    }
    protected function generateLaporan4($nomor_pengajuan)
    {
        return '';
    }
}

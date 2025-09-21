<?php

namespace App\Filament\Resources\PengajuanUMKResource\Pages;

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
use App\Filament\Resources\PengajuanUMKResource;

class ListPengajuanUMKS extends ListRecords
{
    protected static string $resource = PengajuanUMKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add New'),

            Actions\Action::make('Surat Permintaan')
                ->icon('heroicon-o-folder-arrow-down')
                ->form([
                    Select::make('kode_pengajuan')
                        ->label('Nomor Pengajuan')
                        ->options(
                            DB::table('view_pengajuanumk')
                                ->orderByDesc('tanggal_pengajuan')
                                ->pluck('kode_pengajuan', 'kode_pengajuan')
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $kode_pengajuan = $data['kode_pengajuan'];

                    $pengajuan = DB::table('view_pengajuanumk')
                        ->where('kode_pengajuan', $kode_pengajuan)
                        ->get();

                    if ($pengajuan->isEmpty()) {
                        abort(404, 'Data pengajuan tidak ditemukan');
                    }

                    // $total_pengajuan = $pengajuan->sum('total_pengajuan');
                    $total_pengajuan = "10000000.0";

                    Config::set('terbilang.locale', 'id');
                    $terbilang = Terbilang::make($total_pengajuan, ' rupiah');

                    $user = Auth::user();
                    $userName = $user ? $user->name : 'Admin';

                    $tanggal = $pengajuan->first()->tanggal_pengajuan;
                    $formattedDate = Carbon::parse($tanggal)->translatedFormat('d F Y');

                    $path = 'logo.jpg';
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $dataImg = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);

                    $filename = "Permintaan UMK_$kode_pengajuan.pdf";

                    // dd([
                    //     'transaksis' => $pengajuan,
                    //     'image'      => $base64,
                    //     'userName'   => $userName,
                    //     'tanggal'    => $formattedDate,
                    //     'nomor'      => $kode_pengajuan,
                    //     'total_pengajuan' => $total_pengajuan,
                    //     'terbilang'  => $terbilang,
                    // ]);

                    return response()->stream(function () use ($pengajuan, $base64, $formattedDate, $kode_pengajuan, $total_pengajuan, $userName, $terbilang) {
                        echo Pdf::loadView('SuratPembayaran', [
                            'transaksis' => $pengajuan,
                            'image'      => $base64,
                            'userName'   => $userName,
                            'tanggal'    => $formattedDate,
                            'nomor'      => $kode_pengajuan,
                            'total_pengajuan' => $total_pengajuan,
                            'terbilang'  => $terbilang,
                        ])->output();
                    }, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                    ]);
                })
        ];
    }
}

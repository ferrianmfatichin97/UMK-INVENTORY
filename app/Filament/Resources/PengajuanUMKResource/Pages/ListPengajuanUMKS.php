<?php

namespace App\Filament\Resources\PengajuanUMKResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Models\PengajuanUMK;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pengajuan_detail;
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

            Actions\Action::make('Surat Pembayaran')
                ->icon('heroicon-o-folder-arrow-down')
                ->form([
                    Select::make('nomor_pengajuan')
                        ->label('Pengajuan')
                        ->options(PengajuanUMK::query()->pluck('nomor_pengajuan', 'nomor_pengajuan'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $nomor_pengajuan = $data['nomor_pengajuan'];
                    $pengajuan = DB::table('pengajuan_details')
                        ->where('nomor_pengajuan', $nomor_pengajuan)
                        ->get();


                    // dd([
                    //     'nomor_pengajuan' => $nomor_pengajuan,
                    //     'pengajuan' => $pengajuan,
                    // ]);

                    $total_pengajuan = $pengajuan->sum('jumlah');

                    Config::set('terbilang.locale', 'id');
                    $terbilang = Terbilang::make($total_pengajuan, ' rupiah');

                    $user = Auth::user();
                    $userName = $user ? $user->name : 'Admin';

                    $tanggal = DB::table('pengajuanumk')
                        ->where('nomor_pengajuan', $nomor_pengajuan)
                        ->value('tanggal_pengajuan');
                    $formattedDate = Carbon::parse($tanggal)->translatedFormat('d F Y');

                    // dd([
                    //     'transaksis' => $pengajuan,
                    //     'userName' => $userName,
                    //     'tanggal' => $formattedDate,
                    //     'nomor' => $nomor_pengajuan,
                    //     'total_pengajuan' => $total_pengajuan,
                    //     'terbilang' => $terbilang,
                    // ]);

                    $path = 'logo.jpg';
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    $filename = "Pembayaran UMK_$nomor_pengajuan.pdf";

                    return response()->stream(function () use ($pengajuan, $base64, $formattedDate, $nomor_pengajuan, $total_pengajuan, $userName, $terbilang) {
                        echo Pdf::loadView('SuratPembayaran', [
                            'transaksis' => $pengajuan,
                            'image' => $base64,
                            'userName' => $userName,
                            'tanggal' => $formattedDate,
                            'nomor' => $nomor_pengajuan,
                            'total_pengajuan' => $total_pengajuan,
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

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
                        ->required(),
                ])
                ->action(function (array $data) {
                    $nomor_pengajuan_detail = $data['nomor_pengajuan'];
                    $pengajuan = PengajuanUMK::where('nomor_pengajuan', 'LIKE', "%{$nomor_pengajuan_detail}%")->first();

                    if (!$pengajuan) {
                        return response()->json(['message' => 'Pengajuan tidak ditemukan'], 404);
                    }

                    $detail = $pengajuan->pengajuan_detail;

                    // dd([
                    //     'nomor_pengajuan' => $nomor_pengajuan_detail,
                    //     'pengajuan' => $pengajuan,
                    //     'detail' => $detail,
                    // ]);

                    $total_pengajuan = $pengajuan->total_pengajuan;

                    $duit = 5000000;
                    Config::set('terbilang.locale', 'id');
                    $terbilang = Terbilang::make($total_pengajuan, ' rupiah');

                    $user = Auth::user();
                    $userName = $user ? $user->name : 'Admin';

                    $tanggal = $pengajuan->created_at;
                    Carbon::setLocale('id');
                    $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $tanggal);
                    $formattedDate = $carbonDate->translatedFormat('d F Y');

                    // dd([
                    //     'transaksis' => $detail,
                    //     'userName' => $userName,
                    //     'tanggal' => $formattedDate,
                    //     'nomor' => $nomor_pengajuan_detail,
                    //     'total_pengajuan' => $total_pengajuan,
                    //     'terbilang' => $terbilang,
                    // ]);

                    $path = 'logo.jpg';
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    $filename = "Pembayaran UMK_$nomor_pengajuan_detail.pdf";

                    return response()->stream(function () use ($detail, $base64, $formattedDate, $nomor_pengajuan_detail, $total_pengajuan, $userName, $terbilang) {
                        echo Pdf::loadView('SuratPembayaran', [
                            'transaksis' => $detail,
                            'image' => $base64,
                            'userName' => $userName,
                            'tanggal' => $formattedDate,
                            'nomor' => $nomor_pengajuan_detail,
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

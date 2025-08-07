<?php

namespace App\Filament\Resources\TransaksiKeuanganResource\Pages;

use App\Filament\Resources\TransaksiKeuanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;


class CreateTransaksiKeuangan extends CreateRecord
{
    protected static string $resource = TransaksiKeuanganResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        $roleCodeMap = [
            'admin' => '01',
            'umum' => '02',
            'bisnis' => '03',
            'akunting' => '04',
        ];

        $kodeDivisi = $roleCodeMap[strtolower($user->roles)] ?? '00';

        $akun = $data['kode_akun']; 

        if (preg_match('/\.(\d{3})/', $akun, $matches)) {
            $coaCode = $matches[1];
        }
        elseif (preg_match('/-(\d{3})\./', $akun, $matches)) {
            $coaCode = $matches[1];
        } else {
            $coaCode = '000'; 
        }

        $tanggal = \Carbon\Carbon::parse($data['trans_date'])->format('Ymd');
        $count = \App\Models\TransaksiKeuangan::whereDate('trans_date', $data['trans_date'])->count() + 1;
        $urutan = str_pad($count, 5, '0', STR_PAD_LEFT);

        $data['trans_id'] = "{$kodeDivisi}.{$coaCode}.{$tanggal}.{$urutan}";
        $data['status'] = 'posting';
        $data['source'] = $user->roles;
        $data['name'] = $user->name;

        // dd($data);

        Log::info("Transaksi Keuangan created", $data);

        return $data;
    }
}

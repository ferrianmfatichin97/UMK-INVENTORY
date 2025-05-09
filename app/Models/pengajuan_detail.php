<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengajuan_detail extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_details';

    protected $fillable = [
        'nomor_pengajuan',
        'kode_akun',
        'nama_akun',
        'jumlah',
        'keterangan',
    ];

    protected static function booted()
    {
        static::creating(function ($pengajuan) {
            if (empty($pengajuan->nomor_pengajuan)) {
                $bulanTahun = date('m') . date('y');
                $lastPengajuan = self::orderBy('nomor_pengajuan', 'desc')->first();
                $nomorUrut = $lastPengajuan ? intval(substr($lastPengajuan->nomor_pengajuan, 8, 5)) + 1 : 1;
                $formattedNomorUrut = str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);
                $pengajuan->nomor_pengajuan = "SP2UMKU-{$formattedNomorUrut}/K1.01/{$bulanTahun}";
            }
        });
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $bulanTahun = date('m') . date('y');
        $lastPengajuan = \App\Models\PengajuanUMK::orderBy('nomor_pengajuan', 'desc')->first();
        $nomorUrut = $lastPengajuan ? intval(substr($lastPengajuan->nomor_pengajuan, 8, 5)) + 1 : 1;
        $formattedNomorUrut = str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);
        $data['nomor_pengajuan'] = "SP2UMKU-{$formattedNomorUrut}/K1.01/{$bulanTahun}";

        return $data;
    }


    public function pengajuan()
    {
        return $this->belongsTo(PengajuanUMK::class, 'nomor_pengajuan');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengajuan_detail extends Model
{
    use HasFactory;



    protected $table = 'pengajuan_details';

    protected $fillable = [
        //'nomor_pengajuan'=> $nomor_pengajuan,
        'nomor_pengajuan',
        'kode_akun',
        'nama_akun',
        'jumlah',
        'keterangan',
    ];

    // public function nomor_pengajuan()
    // {
    //     $bulanTahun = date('m') . date('y');
    //     $lastPengajuan = PengajuanUMK::orderBy('id', 'desc')->first();
    //     $nomorUrut = $lastPengajuan ? intval(substr($lastPengajuan->nomor_pengajuan, 8, 5)) + 1 : 1;
    //     $formattedNomorUrut = str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);
    //     $nomorPengajuan = "SP2UMKU-{$formattedNomorUrut}/K1.01/{$bulanTahun}";
    //     return $nomorPengajuan;
    // }

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanUMK::class,'nomor_pengajuan');
    }
}

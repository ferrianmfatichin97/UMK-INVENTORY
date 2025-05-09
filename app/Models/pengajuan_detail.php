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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->pengajuan) {
                $model->nomor_pengajuan = $model->pengajuan->nomor_pengajuan;
            }
        });
    }

    public function generateNomorPengajuan()
    {
        $bulanTahun = date('m') . date('y');
        $lastPengajuan = PengajuanUMK::orderBy('id', 'desc')->first();
        $nomorUrut = $lastPengajuan ? intval(substr($lastPengajuan->nomor_pengajuan, 8, 5)) + 1 : 1;
        $formattedNomorUrut = str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);
        return "SP2UMKU-{$formattedNomorUrut}/K1.01/{$bulanTahun}";
    }

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanUMK::class, 'nomor_pengajuan');
    }
}

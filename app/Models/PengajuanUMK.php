<?php

namespace App\Models;

use App\Models\pengajuan_detail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class PengajuanUMK extends Model
{
    use HasFactory;
    protected $table = 'pengajuanumk';

    protected $fillable = [
        'nomor_pengajuan',
        'tanggal_pengajuan',
        'total_pengajuan',
        'total_disetujui',
        'keterangan',
        'status',
    ];

    public function pengajuan_detail()
    {
        return $this->hasMany(pengajuan_detail::class, 'nomor_pengajuan');
    }

    public static function pengajuan($nomor_pengajuan)
    {
        return self::where('nomor_pengajuan', $nomor_pengajuan)->get();
    }

    public function getDetailWithTotal($nomor_pengajuan)
    {
        return $this->pengajuan_detail()
            ->select('kode_akun', 'nama_akun', 'jumlah', 'keterangan', 'create_at')
            ->where('nomor_pengajuan', $nomor_pengajuan)
            ->groupBy('kode_akun', 'nama_akun', 'keterangan')
            ->selectRaw('SUM(CAST(jumlah AS DECIMAL(15, 2))) AS total_nominal')
            ->get();
    }

    public function transaksiUMK()
    {
        return $this->hasMany(TransaksiUMK::class, 'no_pengajuan');
    }

    public function getTransaksiUMKWithTotal($no_pengajuan)
    {
        return $this->transaksiUMK()
            ->select('akun_bpr', 'nama_akun')
            ->selectRaw('SUM(CAST(nominal AS DECIMAL(15, 2))) AS total_nominal')
            ->where('no_pengajuan', $no_pengajuan)
            ->groupBy('akun_bpr', 'nama_akun')
            ->get();
    }
}

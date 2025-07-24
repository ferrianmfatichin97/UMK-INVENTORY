<?php

namespace App\Models;

use App\Models\PengadaanBarang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengadaanBarangDetail extends Model
{
    use HasFactory;

    protected $table = 'pengadaan_barang_details';

    protected $fillable = [
        'pengadaan_barang_id',
        'nama_barang',
        'jumlah',
        'spesifikasi',
        'link',
        'catatan',
        'harga',
    ];

    // Relasi ke pengadaan barang
    public function pengadaan()
    {
        return $this->belongsTo(PengadaanBarang::class, 'pengadaan_barang_id');
    }
}

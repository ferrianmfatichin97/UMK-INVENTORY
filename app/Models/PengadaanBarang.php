<?php

namespace App\Models;

use App\Models\PengadaanBarang_Detail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengadaanBarang extends Model
{
    use HasFactory;

    protected $table = 'pengadaan_barang';

    protected $fillable = [
        'divisi',
        'nota_dinas',
        'note',
        'urgensi',
        'tanggal_dibutuhkan',
        'lampiran_nodin',
        'lampiran_1',
        'lampiran_2',
        'status',
    ];

    // Relasi ke detail barang
    public function details()
    {
        return $this->hasMany(PengadaanBarangDetail::class, 'pengadaan_barang_id');
    }
}

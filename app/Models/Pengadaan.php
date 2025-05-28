<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengadaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'divisi_id',
        'nama_barang',
        'nota_dinas',
        'jumlah',
        'spesifikasi',
        'alasan',
        'urgensi',
        'tanggal_dibutuhkan',
        'lampiran_file',
        'link_toko_online',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}

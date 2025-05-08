<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiUMK extends Model
{
    use HasFactory;

    protected $table = 'transaksiumk';

    protected $fillable = [
        'no_pengajuan',
        'akun_bpr',
        'nama_akun',
        'tanggal',
        'keterangan',
        'qty',
        'satuan',
        'nominal',
    ];
}

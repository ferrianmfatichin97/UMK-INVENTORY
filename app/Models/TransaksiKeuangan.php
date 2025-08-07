<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKeuangan extends Model
{
    protected $table = 'transaksi_keuangan';

    protected $fillable = [
        'trans_id',
        'kode_akun',
        'nama_akun',
        'amount',
        'trans_date',
        'description',
        'source',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Data_kendaraan extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;
    protected $table = 'data_kendaraan';
    protected $fillable = [
        'jenis_kendaraan',
        'merk',
        'type',
        'no_rangka',
        'no_registrasi',
        'no_bpkb',
        'kantor_cabang',
        'jadwal_pajak',
        'perusahaan_asuransi',
        'asuransi_mulai',
        'asuransi_akhir',
        'deleted_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['jenis_kendaraan', 'no_registrasi']);
    }
}

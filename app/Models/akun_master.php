<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class akun_master extends Model
{
    use HasFactory,LogsActivity, SoftDeletes;
    protected $table = 'akun_masters';
    protected $fillable = [
        'akun_bpr',
        'nama_akun',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['akun_bpr', 'nama_akun']);
    }
}

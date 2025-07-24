<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderSetting extends Model
{
    protected $fillable = [
        'reminder_time', 
        'days_before', 
        'send_email', 
        'send_whatsapp'
    ];

    protected $casts = [
        'days_before' => 'array',
        'reminder_time' => 'datetime:H:i',
    ];
}

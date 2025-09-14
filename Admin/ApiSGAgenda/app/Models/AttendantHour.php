<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendantHour extends Model
{
    protected $table = 'attendant_hours';

    protected $fillable = [
        'attendant_hour_code',
        'attendant_code',
        'attendant',
        'day',
        'time',
        'interval',
        'interval_between_services'
    ];
}

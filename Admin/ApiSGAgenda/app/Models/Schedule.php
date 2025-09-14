<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'attendant_code',
        'attendant',
        'service_code',
        'service',
        'customer_name',
        'customer_phone',
        'day',
        'hour', 
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commissions extends Model
{
    protected $table = 'commissions';

    protected $fillable = [
        'commission_code',
        'owner_code',
        'attendant_code',
        'attendant',
        'service_code',
        'service',
        'service_price',
        'category_code',
        'category',
        'scheduling_code',
        'total_commission',
    ];
}

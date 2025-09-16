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
        'scheduling_code',
        'total_commission',
    ];
}

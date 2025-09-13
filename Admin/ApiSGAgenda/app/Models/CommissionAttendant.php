<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionAttendant extends Model
{
    protected $table = 'commission_attendants';

    protected $fillable = [
        'commission_attendants_code',
        'owner_code',
        'attendant_code',
        'attendant_name',
        'service_code',
        'service_name',
        'category_code',
        'category_name',
        'perc_commission', 
        'fixed_commission',
        'active',
    ];
}

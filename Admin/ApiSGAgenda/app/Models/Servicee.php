<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicee extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'service_code',
        'owner_code',
        'category_code',
        'category',
        'name',
        'price',
        'description',
        'duration_string',
        'duration',
        'is_home_service', 
        'check_availability', 
        'active'
    ];
}

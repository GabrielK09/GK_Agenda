<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Invoicing extends Model
{
    protected $table = 'invoicings';
    
    protected $fillable = [
        'invoicing_code',
        'owner_code',
        'attendant_code',
        'scheduling_code',
        'input_value'
    ];  
}

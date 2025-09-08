<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class URL extends Model
{
    protected $table = 'urls';

    protected $fillable = [
        'owner_code',
        'site_name',
        'site_url'
    ];

    protected $hidden = [
        'updated_at'
    ];
}
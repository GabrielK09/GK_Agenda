<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;

class Attendant extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'attendants';
    
    protected $fillable = [
        'owner_code',
        'attendant_code',
        'name',
        'email',
        'password',
        'is_attendant',
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

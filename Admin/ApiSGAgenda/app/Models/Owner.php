<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
class Owner extends Authenticatable implements CanResetPassword
{ 
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'owners';

    protected $fillable = [
        'owner_code', 
        'name', 
        'email', 
        'phone', 
        'password', 
        'company_name', 
        'trade_name', 
        'cnpj_cpf', 
        'cep', 
        'uf', 
        'address', 
        'complement', 
        'neighborhood', 
        'municipality', 
        'number',
        'active',
        'completed',
        'pix_key',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

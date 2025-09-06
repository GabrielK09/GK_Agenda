<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'owner_code', 
        'company_name', 
        'trade_name', 
        'cnpj_cpf', 
        'phone',
        'mail',
        'date_of_birth',
        'cep', 
        'uf', 
        'address', 
        'complement', 
        'neighborhood', 
        'municipality', 
        'number',
        'sex',
    ];
}

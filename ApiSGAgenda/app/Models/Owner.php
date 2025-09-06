<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $table = 'owners';

    protected $fillable = [
        'owner_code', 
        'company_name', 
        'trade_name', 
        'cnpj_cpf', 
        'cep', 
        'uf', 
        'address', 
        'complement', 
        'neighborhood', 
        'municipality', 
        'number'
    ];
}

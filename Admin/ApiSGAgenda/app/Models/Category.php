<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    
    protected $fillable = [
        'category_code',
        'owner_code',
        'parent_id',
        'parent_category',
        'name',
        'description',
        'active',
    ];
}

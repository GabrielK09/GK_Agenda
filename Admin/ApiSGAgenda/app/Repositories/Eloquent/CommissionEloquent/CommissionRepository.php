<?php

namespace App\Repositories\Eloquent\CommissionEloquent;

use App\Models\{
    CommissionAttendant,
    Attendant,
    Owner
};

class CommissionRepository
{    
    public function create(array $data)
    {
        $owner = $data['ownerCode'];
               
    }
}
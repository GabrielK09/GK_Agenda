<?php

namespace App\Repositories\Eloquent\CommissionEloquent;

use App\Models\{
    CommissionAttendant as Commission,
    Attendant,
    Servicee,
    Category,
    Owner
};

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionAttendantRepository
{   
    public function getAll(int $ownerCode, int $attendantCode)
    {        
        
    }

    public function findByID(int $ownerCode, int $attendantCode)
    {
        
    }
}
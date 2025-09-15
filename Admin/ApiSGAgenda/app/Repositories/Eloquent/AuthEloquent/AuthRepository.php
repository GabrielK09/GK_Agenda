<?php

namespace App\Repositories\Eloquent\AuthEloquent;

use App\Models\Attendant;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\AuthInterface\AuthInterface;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthInterface
{

    public function firstRegisterOwner(array $data)
    {
        $transaction = DB::transaction(function() use ($data) {
            $maxCode = Owner::max('owner_code');
            
            $owner = Owner::create([
                'owner_code' => $maxCode ? $maxCode + 1 : 1, 
                'name' => $data['name'],  
                'email' => $data['email'],  
                'phone' => $data['phone'], 
                'password' => Hash::make($data['password']),       
            ]);

            Log::info($owner);
            $maxCode = Attendant::where('owner_code', $owner->owner_code)->max('attendant_code');
            $attendentOwner = Attendant::create([
                'attendant_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $owner->owner_code,
                'name' => $owner->name,
                'email' => $owner->email,
                'password' => Hash::make($owner->password),
                'is_attendant' => 0
            ]);

            Log::info($attendentOwner);

            return $owner;
        });    
        return $transaction;
    }

    public function completeRegisterOwner(array $data)
    {
        
    }

    public function updateRegisterOwner(array $data)
    {

    }

    public function deleteRegisterOwner(array $data)
    {
        
    }
}
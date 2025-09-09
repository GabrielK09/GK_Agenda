<?php

namespace App\Repositories\Eloquent\AuthEloquent;

use App\Models\Owner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\AuthInterface\AuthInterface;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthInterface {
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
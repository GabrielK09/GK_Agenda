<?php

namespace App\Repositories\Eloquent\AttendantEloquent;

use App\Models\Attendant;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AttendantRepository 
{
    public function getAll(int $ownerCode)
    {
        return Attendant::where('owner_code', $ownerCode)->get();

    }

    public function create(array $data)
    {
        $attendant = DB::transaction(function() use ($data) {
            $maxCode = Attendant::max('attendant_code');
            return Attendant::create([
                'attendant_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $data['ownerCode'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        });

        return $attendant;
    }

    public function findByID(int $ownerCode, int $attendantCode)
    {
        return Attendant::where('attendant_code', $attendantCode)
                        ->where('owner_code', $ownerCode)
                        ->first();
    }

    public function findByMail(string $mail)
    {
        $attendant = Attendant::where('email', $mail)->first();
        if(!$attendant)
        {
            return null;

        }
        
        return $attendant;
    }

    public function update(array $data, int $attendantCode)
    {
        $id = DB::transaction(function () use ($data, $attendantCode) {
            $attendant = $this->findByID($data['ownerCode'], $attendantCode);
            
            if(!$attendant)
            {
                throw new Exception("Erro ao localizar o atendente para alteração");

            }

            $attendant->update([

            ]); 

            return $attendant;
        });

        Log::info($id);
        return $id;

    }
}
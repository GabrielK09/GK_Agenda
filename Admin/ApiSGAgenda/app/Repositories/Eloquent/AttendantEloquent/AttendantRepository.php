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
            $maxCode = Attendant::where('owner_code', $data['ownerCode'])->max('attendant_code');
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

    public function update(array $data, int $ownerCode, int $attendantCode)
    {
        $transaction = DB::transaction(function () use ($data, $ownerCode, $attendantCode) {
            $attendant = $this->findByID($ownerCode, $attendantCode);
            
            if(!$attendant)
            {
                throw new Exception("Erro ao localizar o atendente para alteração");

            }

            $attendant->update([
                'name' => $data['name']

            ]); 

            return $attendant;
        });

        Log::info($transaction);
        return $transaction;

    }

    public function active(int $ownerCode, int $attendantCode)
    {
        $transaction = DB::transaction(function () use ($ownerCode, $attendantCode) {
            $attendant = $this->findByID($ownerCode, $attendantCode);
            
            if(!$attendant)
            {
                throw new Exception("Erro ao localizar o atendente para alteração");

            }

            $attendant->update([
                'active' => 1

            ]); 

            return $attendant;
        });

        Log::info($transaction);
        return $transaction;       
    }

    public function delete(int $ownerCode, int $attendantCode)
    {
        $transaction = DB::transaction(function () use ($ownerCode, $attendantCode) {
            $attendant = $this->findByID($ownerCode, $attendantCode);
            
            if(!$attendant)
            {
                throw new Exception("Erro ao localizar o atendente para alteração");

            }

            $attendant->update([
                'active' => 0

            ]); 

            return $attendant;
        });

        Log::info($transaction);
        return $transaction;    

    }
}
<?php

namespace App\Repositories\Eloquent\AttendantEloquent;

use App\Models\Attendant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendantRepository {

    public function create(array $data)
    {
        $attendant = DB::transaction(function() use ($data) {
            Attendant::create([
                'owner_code' => $data['ownerCode'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
        });

        return $attendant;
    }

    public function findByID(int $ownerCode, int $attendantCode)
    {
        return Attendant::where('id', $attendantCode)->first();
    }

    public function findByMail(string $mail)
    {
        return Attendant::where('email', $mail)->first();
    }

    public function update(array $data, int $attendantCode)
    {
        $id = DB::transaction(function () use ($data, $attendantCode) {
            $attendant = $this->findByID($data['ownerCode'], $attendantCode);
            
            return $attendant;
        });

        Log::info($id);
        return $id;

    }
}
<?php

namespace App\Repositories\Eloquent\AttendantEloquent;

use App\Models\Attendant;
use App\Models\AttendantHour;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AttendantHoursRepository 
{
    public function create(array $data)
    {
        $attendant = DB::transaction(function() use ($data) {
            $maxCode = AttendantHour::where('owner_code', $data['ownerCode'])->max('attendant_hour_code');
            $attendant = Attendant::where('owner_code', $data['ownerCode'])->where('attendant_code', $data['attendantCode'])->first();

            return AttendantHour::create([
                'attendant_hour_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $data['ownerCode'],
                'attendant_code' => $attendant->attendant_code,
                'attendant' => $attendant->name,
                'day' => $data['day'],
                'time' => $data['time'],
                'interval' => $data['interval'],
                'interval_between_services' => $data['intervalBetweenServices']
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
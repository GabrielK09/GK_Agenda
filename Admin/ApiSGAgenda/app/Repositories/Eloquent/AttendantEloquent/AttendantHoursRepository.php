<?php

namespace App\Repositories\Eloquent\AttendantEloquent;

use App\Models\Attendant;
use App\Models\AttendantHour;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AttendantHoursRepository 
{

    public function getHours(int $ownerCode, int $attendantCode)
    {
        $hours = AttendantHour::where('owner_code', $ownerCode)->where('attendant_code', $attendantCode)->get();
        return $hours;
    }

    protected function checkExistHours(int $ownerCode, int $attendantCode): bool
    {  
        Log::info('Vai conferir se já não existe horário cadastrado');
        $hours = AttendantHour::where('owner_code', $ownerCode)->where('attendant_code', $attendantCode)->first();
        if($hours)
        {
            Log::info('Já existe horário cadastrado');
            return true;
        }

        Log::info('Não existe horário cadastrado');

        return false;
    }

    public function create(array $data)
    {
        Log::debug($data);

        if($this->checkExistHours($data['ownerCode'], $data['attendantCode']))
        {
            return 'callUpdate';

        } else {
            $hours = DB::transaction(function() use ($data) {
                Log::debug($data['ownerCode']);
                $attendant = Attendant::where('owner_code', $data['ownerCode'])->where('attendant_code', $data['attendantCode'])->first();
                $maxCode = AttendantHour::where('owner_code', $data['ownerCode'])->max('attendant_hour_code');

                Log::debug('Entrando no foreach');
                foreach ($data['hours'] as $days) {
                    $hours = AttendantHour::create([
                        'attendant_hour_code' => $maxCode ? $maxCode + 1 : 1,
                        'owner_code' => $data['ownerCode'],
                        'attendant_code' => $attendant->attendant_code,
                        'attendant' => $attendant->name,
                        'day' => $days['day'],
                        'start' => $days['start'],
                        'end' => $days['end'],
                        'interval' => $days['interval'],
                        'interval_between_services' => $days['intervalBetweenServices'],
                        'marked_day' => $days ['markedDay']
                        
                    ]);
                }

                return $hours;
            });

            return $hours;
        };

        Log::info('Horário cadastrado/alterada com sucesso, vai retornar - line 64!');
        Log::info($hours);
        return $hours;
    }

    public function findByID(int $ownerCode, int $attendantCode)
    {
        return Attendant::where('attendant_code', $attendantCode)
                        ->where('owner_code', $ownerCode)
                        ->first();
    }

    public function update(array $data, int $attendantCode)
    {
        Log::info('Começou o update');
        Log::info($data);

        $hours = DB::transaction(function() use ($data, $attendantCode) {
            Log::debug('Entrando no foreach');
            foreach ($data['hours'] as $days) {
                $attendantHour = AttendantHour::where('owner_code', $data['ownerCode'])->where('attendant_code', $attendantCode)->where('day', $days['day'])->first();
                
                $attendantHour->update([
                    'day' => $days['day'],
                    'start' => $days['start'],
                    'end' => $days['end'],
                    'interval' => $days['interval'],
                    'interval_between_services' => $days['intervalBetweenServices'],
                    'marked_day' => $days ['markedDay']
                    
                ]);
            }

            return $attendantHour;
        });

        Log::info('Horários alterados com sucesso!');

        return $hours;

    }
}
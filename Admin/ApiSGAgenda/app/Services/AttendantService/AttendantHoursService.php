<?php

namespace App\Services\AttendantService;

use App\Repositories\Eloquent\AttendantEloquent\AttendantHoursRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class AttendantHoursService {
    public function __construct(
        protected AttendantHoursRepository $attendantHoursRepository
    ){}

    public function getHours(int $ownerCode, int $attendantCode)
    {
        $hours = $this->attendantHoursRepository->getHours($ownerCode, $attendantCode);

        if(!$hours) {
            throw new Exception('Erro ao retornar as horas do atendente!');
        }
        
        return $hours;
    }

    public function create(array $data)
    {
        $hours = $this->attendantHoursRepository->create($data);

        if($hours === 'callUpdate')
        {
            $this->update($data, $data['attendantCode']);

        } else if(!$hours) {
            throw new Exception('Horas de atendente já cadastrada!');
        }
        
        return $hours;
    }

    public function update(array $data, int $attendantCode) 
    {
        Log::info('Vai ser chamado o update');
        $updatedHours = $this->attendantHoursRepository->update($data, $attendantCode);
        Log::info('Fim do chamado do update');
        Log::info($updatedHours);

        if(!$updatedHours) {
            Log::alert($updatedHours);
            throw new Exception('Erro ao alterar os Horários do atendente!');

        }
        
        return $updatedHours;
    }
}
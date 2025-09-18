<?php

namespace App\Services\ScheduleService;

use App\Repositories\Eloquent\ScheduleEloquent\ScheduleRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class ScheduleService 
{
    public function __construct(
        protected ScheduleRepository $scheduleRepository
    ){}

    public function getAll(int $ownerCode)
    {
        $schedules = $this->scheduleRepository->getAll($ownerCode);

        if(!$schedules)
        {
            throw new Exception("Erro ao retornar todos os agendamentos");
        }

        return $schedules;   
    }

    public function getAllBySite(string $siteName)
    {
        $schedules = $this->scheduleRepository->getAllBySite($siteName);

        if(!$schedules)
        {
            throw new Exception("Erro ao retornar todos os agendamentos");
        }

        return $schedules;   
    }

    public function detail(int $ownerCode, int $scheduleCode)
    {
        $schedule = $this->scheduleRepository->detail($ownerCode, $scheduleCode);

        if(!$schedule)
        {
            throw new Exception("Erro retornar os dados do agendamento");
        }

        return $schedule;
    }

    public function create(array $data)
    {
        $schedule = $this->scheduleRepository->create($data);

        if(!$schedule)
        {
            throw new Exception("Erro ao criar o agendamento");
        }

        return $schedule;
    }

    public function finish(array $data)
    {
        $schedule = $this->scheduleRepository->finish($data);

        if(!$schedule)
        {
            Log::alert($schedule);
            throw new Exception("Erro ao finalizar o agendamento");
        }

        return $schedule;
    }

    public function cancel(int $ownerCode, int $attendantCode)
    {
        $schedule = $this->scheduleRepository->cancel($ownerCode, $attendantCode);

        if(!$schedule)
        {
            throw new Exception("Erro ao cancelar o agendamento");
        }

        return $schedule;       
    }
}
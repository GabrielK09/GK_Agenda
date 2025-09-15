<?php

namespace App\Services\ScheduleService;

use App\Repositories\Eloquent\ScheduleEloquent\ScheduleRepository;
use Exception;

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

   public function create(array $data)
   {
        $schedule = $this->scheduleRepository->create($data);

        if(!$schedule)
        {
            throw new Exception("Erro ao agendar");
        }

        return $schedule;
   }
}
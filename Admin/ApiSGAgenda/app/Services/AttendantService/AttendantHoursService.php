<?php

namespace App\Services\AttendantService;

use App\Repositories\Eloquent\AttendantEloquent\AttendantHoursRepository;
use Exception;

class AttendantHoursService {
    public function __construct(
        protected AttendantHoursRepository $attendantHoursRepository
    ){}

    public function create(array $data)
    {
        $hours = $this->attendantHoursRepository->create($data);

        if($hours)
        {
            throw new Exception('Atendente já cadastrado!');

        }
        
        return $hours;
    }

    public function findByID(int $ownerCode, int $attendantCode)
    {
          
    }

    public function update(array $data, int $id) 
    {
       
    }
}
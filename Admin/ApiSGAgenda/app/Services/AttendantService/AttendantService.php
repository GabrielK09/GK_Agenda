<?php

namespace App\Services\AttendantService;

use App\Repositories\Eloquent\AttendantEloquent\AttendantRepository;
use Exception;
class AttendantService {
    public function __construct(
        protected AttendantRepository $attendantRepository
    ){}

    public function getAll(int $ownerCode)
    {
        $attendants = $this->attendantRepository->getAll($ownerCode);

        return $attendants;
    }

    public function create(array $data)
    {
        $attendant = $this->findByMail($data['email']);

        if($attendant)
        {
            throw new Exception('Atendente já cadastrado!');

        }

        $createdAttendant = $this->attendantRepository->create($data);

        if(!$createdAttendant)
        {
            throw new Exception('Erro ao cadastrado o atendente!');

        }
        
        return $createdAttendant;
    }

    public function findByID(int $ownerCode, int $attendantCode)
    {
        $attendant = $this->attendantRepository->findByID($ownerCode, $attendantCode);

        if (!$attendant) {
            return null;

        }
        
        return $attendant;   
    }

    public function findByMail(string $mail)
    {
        $attendant = $this->attendantRepository->findByMail($mail);

        if (!$attendant) {
            return null;

        }
        
        return $attendant;   
    }

    public function update(array $data, int $ownerCode, int $attendantCode)
    {
        $newAttendant = $this->attendantRepository->update($data, $ownerCode, $attendantCode);

        if(!$newAttendant) 
        {
            throw new Exception("Erro ao alterar os dados do atendente!", 1);

        }

        return $newAttendant;
    }

    public function active(int $ownerCode, int $attendantCode)
    {
        $attendant = $this->attendantRepository->active($ownerCode, $attendantCode);

        if(!$attendant) 
        {
            throw new Exception("Erro ao alterar os dados do atendente!", 1);

        }

        return $attendant;
        
    }

    public function delete(int $ownerCode, int $attendantCode)
    {
        $attendant = $this->attendantRepository->delete($ownerCode, $attendantCode);

        if(!$attendant) 
        {
            throw new Exception("Erro ao alterar os dados do atendente!", 1);

        }

        return $attendant;
        
    }
}
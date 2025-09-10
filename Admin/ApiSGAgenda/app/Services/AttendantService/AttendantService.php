<?php

namespace App\Services\AttendantService;

use App\Repositories\Eloquent\AttendantEloquent\AttendantRepository;
use Exception;

class AttendantService {
    public function __construct(
        protected AttendantRepository $attendantRepository
    ){}

    public function findByID(int $ownerCode, int $id)
    {
        $owner = $this->attendantRepository->findByID($ownerCode, $id);

        if (!$owner) {
            throw new Exception("Erro ao localizar o proprietário!", 1);

        }
        
        return $owner;   
    }

    public function findByMail(string $mail)
    {
        $owner = $this->attendantRepository->findByMail($mail);

        if (!$owner) {
            throw new Exception("Erro ao localizar o proprietário por e-mail!", 1);

        }
        
        return $owner;   
    }

    public function update(array $data, int $id) 
    {
        $newOwner = $this->attendantRepository->update($data, $id);

        if(!$newOwner) 
        {
            throw new Exception("Erro ao alterar os dados do proprietário!", 1);

        }

        return $newOwner;

    }
}
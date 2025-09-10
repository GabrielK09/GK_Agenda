<?php

namespace App\Services\OwnerService;

use App\Repositories\Eloquent\OwnerEloquent\OwnerRepository;
use Exception;

class OwnerService {
    public function __construct(
        protected OwnerRepository $ownerRepository
    ){}

    public function findByID(int $id)
    {
        $owner = $this->ownerRepository->findByID($id);

        if (!$owner) {
            throw new Exception("Erro ao localizar o proprietário!", 1);

        }
        
        return $owner;   
    }


    public function findByMail(string $mail)
    {
        $owner = $this->ownerRepository->findByMail($mail);

        return $owner;   
    }

    public function update(array $data, int $id) 
    {
        $newOwner = $this->ownerRepository->update($data, $id);

        if(!$newOwner) 
        {
            throw new Exception("Erro ao alterar os dados do proprietário!", 1);

        }

        return $newOwner;

    }

    public function getNameApp(int $ownerCode)
    {
        $name = $this->ownerRepository->getNameApp($ownerCode);

        if (!$name) {
            throw new Exception("Erro ao retornar o nome do site ( url )!");
        }

        return $name;
    }
}
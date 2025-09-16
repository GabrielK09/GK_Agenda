<?php

namespace App\Services\ServicesManagementService;

use App\Repositories\Eloquent\ServicesManagementEloquent\ServicesManagementRepository;
use Exception;

class ServicesManagementService 
{
    public function __construct(
        protected ServicesManagementRepository $servicesManagementRepository
    ){}

    public function getAll(int $id)
    {           
        $allServices = $this->servicesManagementRepository->getAll($id);
        
        if(!$allServices)
        {
            throw new Exception("Erro ao localizar todos os serviços!", 1);
        }

        return $allServices;
    }  

    public function getAllNotHasCommission(int $id)
    {
        $allServices = $this->servicesManagementRepository->getAllNotHasCommission($id);

        return $allServices;

    }

    public function create(array $data)
    {
        $service = $this->servicesManagementRepository->create($data);

        if(!$service)
        {
            throw new Exception("Erro ao cadastrar o serviço", 1);
        }

        return $service;
    }

    public function findByID(int $ownerCode, int $serviceCode)
    {
        $service = $this->servicesManagementRepository->findByID($ownerCode, $serviceCode);

        if(!$service)
        {
            throw new Exception("Erro ao buscar o serviço: {$service}", 1);
        };

        return $service;
    }

    public function update(array $data, int $ownerCode, int $serviceCode)
    {
        $service = $this->servicesManagementRepository->update($data, $ownerCode, $serviceCode);

        if(!$service)
        {
            throw new Exception("Erro ao alterar o serviço: {$service}", 1);
        };

        return $service;
    }

    public function delete(int $ownerCode, int $serviceCode)
    {
        $product = $this->servicesManagementRepository->delete($ownerCode, $serviceCode);

        if(!$product)
        {
            throw new Exception("Erro ao deletar o serviço: {$product}", 1);
        };

        return $product;
    }

    public function active(int $ownerCode, int $serviceCode)
    {
        $product = $this->servicesManagementRepository->active($ownerCode, $serviceCode);

        if(!$product)
        {
            throw new Exception("Erro ao ativar o serviço: {$product}", 1);
        };

        return $product;
    }
}
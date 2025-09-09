<?php

namespace App\Services\ServicesManagementService;

use Exception;

class ServicesManagementService 
{
    public function __construct(
        protected ServicesManagementService $servicesManagementService
    ){}

    public function getAll(int $id): array
    {
        
        return [];
        
    }  

    public function create(array $data)
    {
        $service = $this->servicesManagementService->create($data);

        if(!$service)
        {
            throw new Exception("Erro ao cadastrar o serviço", 1);
        }

        return $service;
    }
}
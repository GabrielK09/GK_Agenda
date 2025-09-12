<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\ServicesManagementRequest;
use App\Services\ServicesManagementService\ServicesManagementService;
use Illuminate\Http\Request;

class ServicesManagementController extends Controller
{
    public function __construct(
        protected ServicesManagementService $servicesManagementService
    ){}

    public function getAll(int $id)
    {
        return apiSuccess('Retornando todos os serviços!', $this->servicesManagementService->getAll($id));        
    }

    public function getAllNotHasCommission(int $id)
    {
        return apiSuccess('Retornando todos os serviços sem comissão!', $this->servicesManagementService->getAllNotHasCommission($id));        
    }

    public function create(ServicesManagementRequest $request) 
    {
        return apiSuccess(
            'Serviço cadastrado com sucesso!', 
            $this->servicesManagementService->create($request->validated())
            
        );
    }

    public function findByID(int $ownerCode, int $serviceCode)
    {
        return apiSuccess('Serviço encontrado!', $this->servicesManagementService->findByID($ownerCode, $serviceCode));

    }

    public function update(ServicesManagementRequest $request, int $ownerCode, int $serviceCode)
    {
        return apiSuccess('Serviço alterado com sucesso!', $this->servicesManagementService->update($request->validated(), $ownerCode, $serviceCode));
    }

}
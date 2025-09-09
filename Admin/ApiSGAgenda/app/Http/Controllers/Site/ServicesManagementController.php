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

    public function create(ServicesManagementRequest $request) 
    {
        return apiSuccess(
            'Serviço cadastrado com sucesso!', 
            $this->servicesManagementService->create($request->validated())
            
        );

    }

    public function findByID(int $id)
    {

    }

    public function update(int $id)
    {
        
    }

}
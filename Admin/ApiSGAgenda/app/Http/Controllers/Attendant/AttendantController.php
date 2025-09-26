<?php

namespace App\Http\Controllers\Attendant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendant\AttendantRequest;
use App\Services\AttendantService\AttendantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendantController extends Controller
{
    public function __construct(
        protected AttendantService $attendantService
    ){}

    public function getAll(int $ownerCode)
    {
        return apiSuccess('Todos os atendentes', $this->attendantService->getAll($ownerCode));
    }
    
    public function create(AttendantRequest $request)
    {
        Log::info('Dados para criação do atendente');
        Log::info($request->validated());
        
        return apiSuccess('Atendente cadastrado com sucesso!', $this->attendantService->create($request->validated()));
    }

    public function update(AttendantRequest $request, int $ownerCode, int $attendantCode)
    {
        Log::info('Dados para alteração do atendente');
        Log::info($request->validated());
        
        $data = $request->validated();
        return apiSuccess('Atendente cadastrado com sucesso!', $this->attendantService->update($data, $ownerCode, $attendantCode));
    }

    public function findByID(int $ownerCode, int $attendantCode)
    {
        return apiSuccess('Atendente localizado com sucesso!', $this->attendantService->findByID($ownerCode, $attendantCode));
    }

    public function active(int $ownerCode, int $attendantCode)
    {
        return apiSuccess('Atendente ativado com sucesso!', $this->attendantService->active($ownerCode, $attendantCode));        
    }

    public function delete(int $ownerCode, int $attendantCode)
    {
        return apiSuccess('Atendente desativado com sucesso!', $this->attendantService->delete($ownerCode, $attendantCode));        

    }
}

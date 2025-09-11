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
        Log::info('Dados para criação in Service');
        Log::info($request->validated());
        
        return apiSuccess('Atendente cadastrado com sucesso!', $this->attendantService->create($request->validated()));
    }

    public function ffindByID(int $ownerCode, int $attendantCode)
    {
        return apiSuccess('Atendente localizado com sucesso!', $this->attendantService->findByID($ownerCode, $attendantCode));
    }
}

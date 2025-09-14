<?php

namespace App\Http\Controllers\Attendant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendant\AttendantHoursRequest;
use App\Services\AttendantService\AttendantHoursService;
use Illuminate\Support\Facades\Log;

class AttendantHoursController extends Controller
{
    public function __construct(
        protected AttendantHoursService $attendantHoursService
    ){}

    public function getAll(int $ownerCode)
    {
        //return apiSuccess('Todos os atendentes', $this->attendantService->getAll($ownerCode));
    }
    
    public function create(AttendantHoursRequest $request)
    {
        Log::info('Dados para criação');
        Log::info($request->validated());
        
        return apiSuccess('Horário cadastrado com sucesso!', $this->attendantHoursService->create($request->validated()));
    }

    public function findByID(int $ownerCode, int $attendantCode)
    {
        //return apiSuccess('Atendente localizado com sucesso!', $this->attendantService->findByID($ownerCode, $attendantCode));
    }
}

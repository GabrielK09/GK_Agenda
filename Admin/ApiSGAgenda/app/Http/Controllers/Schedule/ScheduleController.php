<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\ScheduleRequest;
use App\Services\ScheduleService\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService
    ){}

    public function getAll(int $ownerCode)
    {
        return apiSuccess('Retornando todos os agendamentos!', $this->scheduleService->getAll($ownerCode));
    }

    public function detail(int $ownerCode)
    {

    }

    public function create(ScheduleRequest $request)
    {
        Log::debug('Dados');
        Log::debug($request->all());
        
        return apiSuccess('Agendamento cadastrado com sucesso!', $this->scheduleService->create($request->validated()));
    }
}

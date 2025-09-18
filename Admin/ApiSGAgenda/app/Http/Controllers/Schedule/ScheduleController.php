<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\ScheduleFinishRequest;
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
    
    public function getAllBySite(Request $request, string $siteName)
    {
        Log::debug($request->all());
        return apiSuccess('Retornando todos os agendamentos ( marcados )!', $this->scheduleService->getAllBySite($siteName, $request->input('dateByFilter')));
    }

    public function detail(int $ownerCode, int $scheduleCode)
    {
        return apiSuccess('Retornando os dados do agendamento!', $this->scheduleService->detail($ownerCode, $scheduleCode));
    }

    public function create(ScheduleRequest $request)
    {
        Log::debug('Dados');
        Log::debug($request->all());
        
        return apiSuccess('Agendamento cadastrado com sucesso!', $this->scheduleService->create($request->validated()));
    }

    public function finish(ScheduleFinishRequest $request)
    {
        return apiSuccess('Agendamento finalizado com sucesso!', $this->scheduleService->finish($request->validated()));
    }
    
    public function cancel(int $ownerCode, int $attendantCode)
    {
        return apiSuccess('Agendamento cancelado com sucesso!', $this->scheduleService->cancel($ownerCode, $attendantCode));
    }
}

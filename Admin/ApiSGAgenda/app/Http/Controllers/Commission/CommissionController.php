<?php

namespace App\Http\Controllers\Commission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commission\CommissionRequest;
use App\Services\CommissionService\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommissionController extends Controller
{
    public function __construct(
        protected CommissionService $commissionService
    ){}

    public function getAll(int $ownerCode, int $attendantCode)
    {
        return apiSuccess('Todas as comissões do atendente', $this->commissionService->getAll($ownerCode, $attendantCode));
    }

    public function create(CommissionRequest $request)
    {
        Log::info('Dados recebidos');
        Log::info($request->all());
        return apiSuccess('Comissão criada com sucesso!', $this->commissionService->create($request->validated()));
    }
}

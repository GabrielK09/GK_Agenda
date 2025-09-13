<?php

namespace App\Services\CommissionService;

use App\Repositories\Eloquent\CommissionEloquent\CommissionRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class CommissionService 
{
    public function __construct(
        protected CommissionRepository $commissionRepository
    ){}
    
    public function getAll(int $ownerCode, int $attendantCode)
    {        
        $commissions = $this->commissionRepository->getAll($ownerCode, $attendantCode);

        if(!$commissions)
        {
            throw new Exception("Erro ao retornar todas a comissões");

        }

        return $commissions;
    }

    public function create(array $data)
    {
        $commission = $this->commissionRepository->create($data);
        
        if(!$commission)
        {
            Log::alert('Erro ao gerar a comissão');
            Log::alert($commission);

            throw new Exception("Erro ao gerar a comissão");
        };

        return $commission;
    }
}
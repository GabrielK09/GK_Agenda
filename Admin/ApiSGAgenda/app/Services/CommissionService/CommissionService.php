<?php

namespace App\Services\CommissionService;

use App\Repositories\Eloquent\CommissionEloquent\CommissionRepository;
class CommissionService 
{
    public function __construct(
        protected CommissionRepository $commissionRepository
    ){}
    
    public function create(array $data)
    {
        
    }
}
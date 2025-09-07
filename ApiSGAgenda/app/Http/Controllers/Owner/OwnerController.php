<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\OwnerRequest;
use App\Services\OwnerService\OwnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OwnerController extends Controller
{
    public function __construct(
        protected OwnerService $ownerService
    ){}

    public function update(OwnerRequest $request, int $id) 
    {
        Log::info($request->validated());        
        Log::info($id);        
        return apiSuccess('Dados alterados com sucesso!', $this->ownerService->update($request->validated(), $id));
    }
}

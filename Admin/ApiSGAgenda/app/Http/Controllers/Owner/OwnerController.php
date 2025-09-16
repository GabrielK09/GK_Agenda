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
        return apiSuccess('Dados alterados com sucesso!', $this->ownerService->update($request->validated(), $id));
    }

    public function findByID(int $ownerCode)
    {
        return apiSuccess('Dados retornados com sucesso!', $this->ownerService->findByID($ownerCode));
    }

    public function pixKey(int $ownerCode, Request $request)
    {
        return apiSuccess('Dados retornados com sucesso!', $this->ownerService->pixKey($ownerCode, $request->input('pix')));
    }
}

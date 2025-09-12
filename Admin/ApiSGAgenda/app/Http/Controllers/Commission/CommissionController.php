<?php

namespace App\Http\Controllers\Commission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commission\CommissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommissionController extends Controller
{
    public function create(CommissionRequest $request)
    {
        Log::info('Dados recebidos');
        Log::info($request->all());
        
    }
}

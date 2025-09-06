<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function registerOwner(AuthRequest $request) {
        Log::info('Dados recebidos:');
        Log::info($request->validated());

    }
}

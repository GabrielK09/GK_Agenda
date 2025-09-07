<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{
    AuthLoginRequest,
    AuthRequest
};

use Illuminate\Support\Facades\Log;
use App\Services\AuthService\AuthService;
use App\Services\OwnerService\OwnerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected OwnerService $ownerService
    ){}

    public function firstRegisterOwner(AuthRequest $request) 
    {
        return apiSuccess('Proprietário cadastrado com sucesso!', $this->authService->firstRegisterOwner($request->validated()), true, 201);
    }

    public function auth(AuthLoginRequest $request) 
    {
        $data = $request->validated();
        $owner = $this->ownerService->findByMail($data['email']);

        $password = $data['password'];

        Log::info("Login: {$owner->email}, senha: {$password}");

        if($owner && Hash::check($password, $owner->password))
        {
            Log::info('As senhas são iguais');
            Auth::login($owner);
            $token = $owner->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token
            ]);

        } else {
            Log::info('As senhas não são iguais');

        }
    }
}

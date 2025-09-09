<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{
    AuthLoginRequest,
    AuthRequest
};
use App\Services\AttendantService\AttendantService;
use Illuminate\Support\Facades\Log;
use App\Services\AuthService\AuthService;
use App\Services\OwnerService\OwnerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected OwnerService $ownerService,
        protected AttendantService $attendantService
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
            $siteName = $this->ownerService->getNameApp($owner->owner_code);
    
            return response()->json([
                'success' => true,
                'isAttendant' => false,
                'ownerCode' => $owner->owner_code,
                'siteName' => $siteName,
                'message' => 'Login bem sucedido!',
                'token' => $token

            ]);

        } else if (!$owner) {
            Log::info("E-mail: {$data['email']} não localizado na base de dados");
            Log::info("Conferindo se não é um atendente...");
            $attendant = $this->attendantService->findByMail($data['email']);
            if($attendant && Hash::check($password, $attendant->password)) 
            {
                Auth::login($attendant);

                $attendantToken = $attendant->createToken('auth_token')->plainTextToken;
                $siteName = $this->ownerService->getNameApp($attendant->owner_code);

                return response()->json([
                    'success' => true,
                    'isAttendant' => true,
                    'ownerCode' => $owner->owner_code,
                    'siteName' => $siteName,
                    'message' => 'Login bem sucedido!',
                    'token' => $attendantToken

                ]);

            } else if ($attendant) {
                Log::info("E-mail: {$data['email']} também não localizado na base de dados");
                return apiError("E-mail: {$data['email']} não localizado na base de dados");

            } else {
                Log::info('As senhas não são iguais');
                return apiError('Credencias incorretas!');
                
            }
            return apiError("E-mail: {$data['email']} não localizado na base de dados");
        } else {
            Log::info('As senhas não são iguais');
            return apiError('Credencias incorretas!');

        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'Logout bem sucedido!'
        ]);
    }
}

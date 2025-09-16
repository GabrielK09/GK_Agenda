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
        Log::info($data);
        $owner = $this->ownerService->findByMail($data['email']);

        $password = $data['password'];
        Log::info($owner);
    
        if($owner && Hash::check($password, $owner->password))
        {
            if(is_null($owner->cnpj_cpf))
            {
                Log::warning('Proprietário não possui a empresa cadastrada');
                return response()->json([
                    'success' => false,
                    'ownerCode' => $owner->owner_code,
                    'route' => 'complete-register',
                    'message' => 'Cadastro pendente!',

                ]);
            } else if (!$this->ownerService->getNameApp($owner->owner_code)) {
                Log::warning('Proprietário não possui a URL do site cadastrada');
                return response()->json([
                    'success' => false,
                    'ownerCode' => $owner->owner_code,
                    'route' => 'complete-register',
                    'message' => 'Cadastro pendente!',

                ]);
            } else if(!$owner->completed) {
                Log::warning('Proprietário não terminou o cadastro');
                return response()->json([
                    'success' => false,
                    'ownerCode' => $owner->owner_code,
                    'route' => 'complete-register',
                    'message' => 'Cadastro pendente!',

                ]);
            }
            
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
            Log::info($attendant);

            if($attendant && Hash::check($password, $attendant->password)) 
            {
                Auth::login($attendant);

                $attendantToken = $attendant->createToken('auth_token')->plainTextToken;
                $siteName = $this->ownerService->getNameApp($attendant->owner_code);

                return response()->json([
                    'success' => true,
                    'isAttendant' => true,
                    'ownerCode' => $attendant->owner_code,
                    'siteName' => $siteName,
                    'message' => 'Login bem sucedido!',
                    'token' => $attendantToken

                ]);

            } else if (!$attendant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credencias incorretas!'
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Credencias incorretas!'
                ]);
            }

            Log::warning('Não está cadastrado');
            return response()->json([
                'success' => false,
                'message' => 'Credencias incorretas!'
            ]);
            
        } else {
            Log::warning('As senhas não são iguais');
            return response()->json([
                'success' => false,
                'message' => 'Credencias incorretas!'
            ]);
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

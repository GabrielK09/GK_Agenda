<?php

namespace App\Services\AuthService;

use App\Repositories\Eloquent\AuthEloquent\AuthRepository;
use Exception;

class AuthService {
    public function __construct(
        protected AuthRepository $authRepository
    ){}

    public function firstRegisterOwner(array $data)
    {
        $owner = $this->authRepository->firstRegisterOwner($data);

        if (!$owner) {
            throw new Exception("Erro ao cadastrar o proprietário!", 1);

        }

        return $owner;
    }
}
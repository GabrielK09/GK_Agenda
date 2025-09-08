<?php

namespace App\Repositories\Interfaces\AuthInterface;

interface AuthInterface {
    public function firstRegisterOwner(array $data);
    public function completeRegisterOwner(array $data);
    public function updateRegisterOwner(array $data);
    public function deleteRegisterOwner(array $data);

}
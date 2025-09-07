<?php

namespace App\Repositories\Interfaces\OwnerInterface;

interface OwnerInterface {
    public function findByID(int $id);
    public function findByMail(string $mail);
    

}
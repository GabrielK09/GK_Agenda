<?php

namespace App\DTO\OwnerDTO;

class OwnerDTO {
    public function __construct(
        public string $name, 
        public string $email, 
        public string $phone, 
        public string $password, 

    ){}
}
<?php

namespace App\Services\URLService;

use App\Repositories\Eloquent\URLEloquent\URLRepository;
use Exception;

class URLService 
{
    public function __construct(
        protected URLRepository $urlRepository
    ){}

    public function createURL(string $url, string $urlName, int $ownerID)
    {
        $url = $this->urlRepository->createURL($url, $urlName, $ownerID);

        if (!$url) 
        {
            throw new Exception("Erro ao criar a URL do site", 1);   
        }

        return $url;
    }
}
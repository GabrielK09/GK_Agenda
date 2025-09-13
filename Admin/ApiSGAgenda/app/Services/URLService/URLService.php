<?php

namespace App\Services\URLService;

use App\Repositories\Eloquent\URLEloquent\URLRepository;
use Exception;

class URLService 
{
    public function __construct(
        protected URLRepository $urlRepository
    ){}

    public function getURL(int $ownerCode)
    {
        $url = $this->urlRepository->getURL($ownerCode);

        if (!$url) 
        {
            throw new Exception("Erro ao retornar a URL do site", 1);   
        }

        return $url;
    }

    public function createURL(string $url, string $urlName, int $ownerCode)
    {
        $url = $this->urlRepository->createURL($url, $urlName, $ownerCode);

        if (!$url) 
        {
            throw new Exception("Erro ao criar a URL do site", 1);   
        }

        return $url;
    }

    public function getCategories(string $siteName)
    {
        $categories = $this->urlRepository->getCategories($siteName);

        if(!$categories) 
        {
            throw new Exception('Erro ao retornar as categorias para o site', 1); 
        }

        return $categories;
    }

    public function getServices(string $siteName)
    {
       $services = $this->urlRepository->getServices($siteName);

       if(!$services) 
       {
            throw new Exception('Erro ao retornar os serviços para o site', 1);
       }

       return $services;
    }
}
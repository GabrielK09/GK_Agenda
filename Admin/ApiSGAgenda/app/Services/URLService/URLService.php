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

    public function findServiceByID(string $siteName, int $serviceCode)
    {
        $service = $this->urlRepository->findServiceByID($siteName, $serviceCode);
        
        if(!$service) 
        {
            throw new Exception('Erro ao retornar os serviço para o site', 1);
        }

        return $service;
    }

    public function getAttendants(string $siteName)
    {
        $attendants = $this->urlRepository->getAttendants($siteName);

        if(!$attendants) 
        {
            throw new Exception('Erro ao retornar os atendentes para o site', 1);
        }

        return $attendants;
    }

    public function getAttendantHour(string $siteName, int $attendantCode)
    {    
        $attendantHour = $this->urlRepository->getAttendantHour($siteName, $attendantCode);

        if(!$attendantHour) 
        {
            throw new Exception('Erro ao retornar os horários do atendente para o site', 1);
        }

        return $attendantHour;
    }

    public function saveSiteSettings(array $data)
    {
        $siteSettings = $this->urlRepository->saveSiteSettings($data);

        if(!$siteSettings) 
        {
            throw new Exception('Erro ao gravar configurações para o site', 1);
        }

        return $siteSettings;
    }

    public function returnSiteSettings(string $siteName)
    {
        $siteSettings = $this->urlRepository->returnSiteSettings($siteName);

        if(!$siteSettings) 
        {
            throw new Exception('Erro ao retornar as configurações para o site', 1);
        }

        return $siteSettings;
    }
}
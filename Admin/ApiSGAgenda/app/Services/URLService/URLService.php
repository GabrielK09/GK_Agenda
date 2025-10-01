<?php

namespace App\Services\URLService;

use App\Repositories\Eloquent\URLEloquent\URLRepository;
use Exception;
use Illuminate\Support\Facades\Log;

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
        $colorData = null;
        $apiURLPrefix = env('THE_COLOR_API');

        Log::info($data);
        Log::info("URL: {$apiURLPrefix}");

        if($data['siteColor'])
        {
            $replaceSiteColor = str_replace('#', '', $data['siteColor']);

            $apiURL = "{$apiURLPrefix}/scheme?hex={$replaceSiteColor}";

            $colorData = $this->fecthApi($apiURL);
        };

        Log::info('data');
        Log::info($data);
        Log::info('colorData');
        Log::info($colorData);

        $siteSettings = $this->urlRepository->saveSiteSettings($data, $colorData);

        if(!$siteSettings) 
        {
            throw new Exception('Erro ao gravar configurações para o site', 1);
        }

        return $siteSettings;
    }

    public function fecthApi(string $url)
    {
        Log::info('fecthApi');

        if($url === '') return [];

        $ch = curl_init();  

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $res = curl_exec($ch);

        if(curl_errno($ch))
        {
            Log::alert("Erro ao consultar a api - {$url}");
            Log::alert(curl_errno($ch));
            curl_close($ch);
            return [];

        };
        
        curl_close($ch);

        $decode = json_decode($res, true);

        if(json_last_error() === JSON_ERROR_NONE) {
            Log::debug('Json API:');
            Log::debug($decode['colors']);

            $newArray = [
                'bgCardColor' => $decode['colors'][3]['hex']['value'],
                'bgBtnColor' => $decode['colors'][4]['hex']['value']

            ];

            Log::debug('Retorno:');
            Log::debug($newArray);
            return $newArray;

        }

        Log::debug($res);

        return ['raw' => $res];
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
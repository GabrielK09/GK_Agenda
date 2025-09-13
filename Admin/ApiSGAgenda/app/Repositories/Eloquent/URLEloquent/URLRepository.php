<?php

namespace App\Repositories\Eloquent\URLEloquent;

use App\Models\URL as SiteURL;
use App\Repositories\Eloquent\CategoriesManagementEloquent\CategoriesManagementRepository;
use App\Repositories\Eloquent\OwnerEloquent\OwnerRepository;
use App\Repositories\Eloquent\ServicesManagementEloquent\ServicesManagementRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class URLRepository 
{
    public function __construct(
        protected OwnerRepository $ownerRepository,
        protected ServicesManagementRepository $servicesManagementRepository,
        protected CategoriesManagementRepository $categoriesManagementRepository
    ){}

    public function getURL(int $onwerCode)
    {
        return SiteURL::where('owner_code', $onwerCode)->first();
    }

    public function createURL(string $url, string $urlName, int $ownerCode)
    {   
        $owner = $this->ownerRepository->findByID($ownerCode);
        if ($owner) 
        {
            Log::info('O owner foi encontrado');
            $urlSite = DB::transaction(function() use ($url, $urlName, $ownerCode, $owner) {
                $site = SiteURL::create([
                    'owner_code' => $ownerCode,
                    'site_name' => $urlName,
                    'site_url' => $url
                ]);

                Log::alert('URLRepository: Vai completar o cadastro do owner');
                $owner->update([
                    'completed' => 1
                ]);

                return $site;
            }); 

            Log::info($urlSite);
            
            return $urlSite;
            
        } else {
            Log::info('O owner não foi encontrado');

            return false;
        }
    }

    protected function getOwnerCode(string $siteName)
    {
        $siteData = SiteURL::where('site_name', $siteName)->first();

        if(!$siteData) return;

        return $siteData->owner_code;
    }

    public function getCategories(string $siteName)
    {
        $ownerCode = $this->getOwnerCode($siteName);
        
        $categories = $this->categoriesManagementRepository->getAll($ownerCode);

        return $categories;
    }

    public function getServices(string $siteName)
    {
        $ownerCode = $this->getOwnerCode($siteName);
        
        $services = $this->servicesManagementRepository->getAll($ownerCode);

        return $services;
    }
}
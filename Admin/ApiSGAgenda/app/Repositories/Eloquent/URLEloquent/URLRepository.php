<?php

namespace App\Repositories\Eloquent\URLEloquent;

use App\Models\{
    SiteSetting,
    URL as SiteURL,
};

use App\Repositories\Eloquent\AttendantEloquent\{
    AttendantHoursRepository,
    AttendantRepository

};

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
        protected CategoriesManagementRepository $categoriesManagementRepository,
        protected AttendantRepository $attendantRepository,
        protected AttendantHoursRepository $attendantHoursRepository
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

                return $owner;
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

    public function findServiceByID(string $siteName, int $serviceCode)
    {
        $ownerCode = $this->getOwnerCode($siteName);
        $service = $this->servicesManagementRepository->findByID($ownerCode, $serviceCode);

        return $service;
    }

    public function getAttendants(string $siteName)
    {
        $ownerCode = $this->getOwnerCode($siteName);
        $attendants = $this->attendantRepository->getAll($ownerCode);

        return $attendants;
    }

    public function getAttendantHour(string $siteName, int $attendantCode)
    {
        $ownerCode = $this->getOwnerCode($siteName);
        $hours = $this->attendantHoursRepository->getHours($ownerCode, $attendantCode);

        return $hours;
    }

    public function saveSiteSettings(array $data)
    {
        $settings = DB::transaction(function() use ($data) {
            $maxCode = SiteSetting::where('owner_code', $data['ownerCode'])->max('site_setting_code');
            if(SiteSetting::where('owner_code', $data['ownerCode'])->first())
            {
                Log::debug('Já tem configuração criada, vai editar');
                $setting = SiteSetting::where('owner_code', $data['ownerCode'])->update([                    
                    'theme_color' => $data['themeColor'],
                    'contact_phone' => $data['contactPhone'],
                    'text_color' => $data['themeColor'] === '#222831' ? '#fff' : '#000',
                    'slogan' => $data['slogan'],
                    
                ]);

                Log::debug($setting);
                return $setting;
                
            } else {
                Log::debug('Não tem configuração criada, vai criar');
                $setting = SiteSetting::create([
                    'site_setting_code' => $maxCode ? $maxCode + 1 : 1,
                    'owner_code' => $data['ownerCode'],
                    'theme_color' => $data['themeColor'],
                    'site_color' => $data['siteColor'],
                    'text_color' => $data['themeColor'] === '#222831' ? '#fff' : '#000',
                    'contact_phone' => $data['contactPhone'],
                    'slogan' => $data['slogan'],

                ]);                
            };

            return $setting;
        });

        return $settings;
    }

    public function returnSiteSettings(string $siteName)
    {
        $ownerCode = $this->getOwnerCode($siteName);
        $settings = SiteSetting::where('owner_code', $ownerCode)->first();

        return $settings;

    }
}
<?php

namespace App\Repositories\Eloquent\URLEloquent;

use App\Models\URL as SiteURL;
use App\Repositories\Eloquent\OwnerEloquent\OwnerRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class URLRepository 
{
    public function __construct(
        protected OwnerRepository $ownerRepository
    ){}

    public function createURL(string $url, string $urlName, int $ownerID)
    {   
        $owner = $this->ownerRepository->findByID($ownerID);
        if ($owner) 
        {
            Log::info('O owner foi encontrado');
            $urlSite = DB::transaction(function() use ($url, $urlName, $ownerID) {
                $site = SiteURL::create([
                    'owner_code' => $ownerID,
                    'site_name' => $urlName,
                    'site_url' => $url
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
}
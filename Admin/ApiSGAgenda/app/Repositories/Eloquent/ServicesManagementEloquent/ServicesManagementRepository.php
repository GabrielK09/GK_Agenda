<?php

namespace App\Repositories\Eloquent\ServicesManagementEloquent;

use App\Models\Category;
use App\Models\CommissionAttendant;
use App\Models\Servicee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServicesManagementRepository 
{
    public function getAll(int $id)
    {
        $services = Servicee::where('owner_code', $id)->get();

        return $services;

    }

    public function getAllNotHasCommission(int $id)
    {
        $allServices = Servicee::where('owner_code', $id)->where('category_code', null)->get();
        $newServices = [];
        foreach ($allServices as $service) {
            Log::debug('Dentro do foreach');
            Log::debug($service);
            $commissionService = CommissionAttendant::where('owner_code', $id)
                                                        ->where('service_code', $service->service_code)
                                                        ->first();

            if($commissionService) 
            {
                continue;
            } else {
                array_push($newServices, $service);
            };
        }

        Log::debug('Fora do foreach');
        Log::debug($newServices);

        return $newServices;
    }


    public function create(array $data) 
    {
        Log::info('Dados para o create');
        Log::info($data);

        $id = DB::transaction(function() use ($data) {
            $category = Category::where('category_code', $data['categoryCode'])
                                  ->where('owner_code', $data['ownerCode'])
                                  ->first();

            $maxCode = Servicee::where('owner_code', $data['ownerCode'])->max('service_code');

            return Servicee::create([
                'service_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $data['ownerCode'],
                'category_code' => $category->category_code ?? null,
                'category' => $category->name ?? null,
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'] ?? '',
                'duration_string' => $data['durationString'],
                'duration' => $data['duration'],
                'is_home_service' => $data['isHomeService'],
                'check_availability' => $data['checkAvailability'],
            ]);
        });

        Log::info($id);

        return $id;
    }

    public function findByID(int $ownerCode, int $serviceCode)
    {
        $service = Servicee::where('active', 1)
                            ->where('owner_code', $ownerCode)
                            ->where('service_code', $serviceCode)
                            ->first();
        if(!$service)
        {
            Log::warning("Serviço não encontado!, owner_code: {$ownerCode} | service_code: {$serviceCode}");
            return;
        }

        Log::info($service);
        
        return $service;
    }   
    
    public function findByCategory(int $ownerCode, int $categoryCode)
    {
        $service = Servicee::where('active', 1)
                            ->where('owner_code', $ownerCode)
                            ->where('category_code', $categoryCode)
                            ->get();
        if(!$service)
        {
            Log::warning("Serviço por categoria não encontado!, owner_code: {$ownerCode} | category_code: {$categoryCode}");
            return;
        }

        Log::info($service);
        
        return $service;
    }   

    public function update(array $data, int $ownerCode, int $serviceCode)
    {
        $id = DB::transaction(function() use ($data, $ownerCode, $serviceCode) {
            $service = $this->findByID($ownerCode, $serviceCode);
            $category = Category::where('category_code', $data['categoryCode'])
                                  ->where('owner_code', $data['ownerCode'])
                                  ->first();

            Log::alert('update service');
            Log::alert($service);

            if(!$service) return;
            
            $service->update([
                'category_code' => $category->category_code ?? null,
                'category' => $category->name ?? null,
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'] ?? '',
                'duration_string' => $data['durationString'],
                'duration' => $data['duration'],
                'is_home_service' => $data['isHomeService'],
                'check_availability' => $data['checkAvailability'],

            ]);

            return $service;
        });

        return $id;
    }

    public function groupServices(int $ownerCode)
    {
        $group = DB::table('schedules')
                    ->where('owner_code', '=', $ownerCode)
                    ->select('service', DB::raw('count(*) as total'))
                    ->groupBy('service')
                    ->get();

        Log::debug($group);
        
    }

    public function delete(int $ownerCode, int $serviceCode)
    {  
        $id = DB::transaction(function() use ($ownerCode, $serviceCode) {
            $service = $this->findByID($ownerCode, $serviceCode);
            
            if(!$service) return;

            return $service->update([
                'active' => 0
            ]);
        });

        return $id;
    }

    public function active(int $ownerCode, int $serviceCode)
    {  
        $id = DB::transaction(function() use ($ownerCode, $serviceCode) {
            $service = $this->findByID($ownerCode, $serviceCode);
            
            if(!$service) return;

            return $service->update([
                'active' => 1
            ]);
        });

        return $id;
    }

    public function removeCategory($ownerCode, $serviceCode)
    {
        Log::debug('Tem produto com categoria que está sendo desativada, vai remover a categoria');
        $removeCategoryByService = DB::transaction(function() use ($ownerCode, $serviceCode) {
            $service = $this->findByID($ownerCode, $serviceCode);

            if(!$service) return;

            $service->update([
                'category_code' => null,
                'category' => null,

            ]);

            return $service;
        });

        return $removeCategoryByService;
    }
}
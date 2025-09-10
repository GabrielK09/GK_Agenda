<?php

namespace App\Repositories\Eloquent\ServicesManagementEloquent;

use App\Models\Category;
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

    public function create(array $data) 
    {
        Log::info('Dados para o create');
        Log::info($data);

        $id = DB::transaction(function() use ($data) {
            $category = Category::where('category_code', $data['categoryCode'])
                                  ->where('owner_code', $data['ownerCode'])
                                  ->first();

            $maxCode = Servicee::max('service_code');

            return Servicee::create([
                'service_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $data['ownerCode'],
                'category_code' => $category->category_code ?? null,
                'category' => $category->name ?? null,
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
                'duration_string' => $data['duration'],
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
        }

        Log::info($service);
        
        return $service;
    }   
}
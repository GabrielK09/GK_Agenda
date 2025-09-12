<?php

namespace App\Repositories\Eloquent\CommissionEloquent;

use App\Models\{
    CommissionAttendant as Commission,
    Attendant,
    Servicee,
    Category,
    Owner
};
use App\Repositories\Eloquent\OwnerEloquent\OwnerRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionRepository
{   
    public function create(array $data)
    {
        Log::info('Dados no repositório');
        Log::info($data);
        $service = null;
        $category = null;
        $owner = Owner::where('owner_code', $data['ownerCode'])->first();

        if(!$owner) return;
        
        $attendant = Attendant::where('owner_code', $owner->owner_code)
                                ->where('active', 1)
                                ->where('attendant_code', $data['attendantCode'])
                                ->first();

        if(!$attendant) return;      

        if(!is_null($data['serviceCode']))
        {
            Log::debug('Possui código de serviço informado');
            $service = Servicee::where('owner_code', $owner->owner_code)
                                ->where('active', 1)
                                ->where('service_code', $data['serviceCode'])
                                ->first();

            Log::debug('Serviço: ');
            Log::debug($service);
            if(!$service) return;

            Log::debug('Serviço encontrado!');
        }
        
        if(!is_null($data['categoryCode']))
        {
            Log::debug('Possui código de categoria informado');
            $category = Category::where('owner_code', $owner->owner_code)
                                ->where('active', 1)
                                ->where('category_code', $data['categoryCode'])
                                ->first();
            if(!$category) return;
        }

        Log::debug('-- Retorno -- ');
        Log::debug($service);
        Log::debug($category);

        $id = DB::transaction(function() use ($attendant, $owner, $category, $service, $data){ 
            $commission = Commission::create([
                'owner_code' => $owner->owner_code,
                'attendant_code' => $attendant->attendant_code,
                'attendant_name' => $attendant->name,
                'service_code' => $service->service_code ?? null,
                'service_name' => $service->name ?? '',
                'category_code' => $category->category_code ?? null,
                'category_name' => $category->name ?? '',
                'perc_commission' => $data['percCommission'], 
                'fixed_commission' => $data['fixCommission'],
            ]);

            Log::info($commission);

            return $commission;
        });

        return $id;
    }
}
<?php

namespace App\Repositories\Eloquent\OwnerEloquent;

use App\Models\Owner;
use App\Models\URL as SiteURL;
use App\Repositories\Interfaces\OwnerInterface\OwnerInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OwnerRepository implements OwnerInterface {
    public function findByID(int $id)
    {
        return Owner::where('id', $id)->first();
    }

    public function findByMail(string $mail)
    {
        $owner = Owner::where('email', $mail)->first();

        if(!$owner)
        {
            return null;
        }
        
        return $owner;
    }

    public function update(array $data, int $id)
    {
        $id = DB::transaction(function () use ($data, $id) {
            $owner = $this->findByID($id);
            $owner->update([
                'company_name' => $data['companyName'], 
                'trade_name' => $data['tradeName'], 
                'cnpj_cpf' => preg_replace('/[^a-zA-Z0-9]/', '', $data['cnpjCpf']),
                'completed' => 1

            ]);
            
            Log::info($owner);
            
            return $owner;
        });

        Log::info($id);
        return $id;

    }

    public function getNameApp(int $ownerCode)
    {
        Log::info($ownerCode);
        $name = SiteURL::where('owner_code', $ownerCode)->first();
        return $name->site_name;
    }
}
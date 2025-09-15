<?php

namespace App\Repositories\Eloquent\ScheduleEloquent;

use App\Models\Attendant;
use App\Models\Schedule;
use App\Models\Servicee;
use App\Models\URL as SiteURL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleRepository 
{
    protected function getOwnerCode(string $siteName)
    {
        $siteData = SiteURL::where('site_name', $siteName)->first();

        if(!$siteData) return;

        return $siteData->owner_code;
    }

    public function getAll(int $id)
    {
        return Schedule::where('owner_code', $id)->get();

    }
    
    public function create(array $data) 
    {
        $schedule = DB::transaction(function() use ($data) {
            $ownerCode = $this->getOwnerCode($data['siteName']);
            $maxCode = Schedule::where('owner_code')->max('scheduling_code');
            $attendant = Attendant::where('owner_code', $ownerCode)->where('attendant_code', $data['attendantCode'])->first();
            $service = Servicee::where('owner_code', $ownerCode)->where('service_code', $data['serviceCode'])->first();
                /*
                'siteName' => 'teste',
                'serviceCode' => 1,
                'attendantCode' => 1,
                'customerName' => 'aaaaaa',
                'customerPhone' => '(11) 11111-1111',
                'date' => '7/dom.',
                'hour' => '10:00',
                */

            return Schedule::create([
                'scheduling_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $ownerCode,
                'attendant_code' => $attendant->attendant_code, 
                'attendant' => $attendant->name,
                'service_code' => $service->service_code,
                'service' => $service->name,
                'customer_name' => $data['customerName'],
                'customer_phone' => $data['customerPhone'],
                'day' => $data['date'],
                'hour' => $data['hour'],
                'month' => $data['month'],
            ]);
        }); 

        return $schedule;
    }
}
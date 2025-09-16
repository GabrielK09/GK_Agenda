<?php

namespace App\Repositories\Eloquent\ScheduleEloquent;

use App\Models\Attendant;
use App\Models\CommissionAttendant;
use App\Models\Commissions;
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

    public function getAll(int $ownerCode)
    {
        return Schedule::where('owner_code', $ownerCode)->where('completed', 0)->where('canceled', 0)->get();

    }

    public function detail(int $ownerCode, int $scheduleCode)
    {
        Log::info($ownerCode);
        Log::info($scheduleCode);
        return Schedule::where('owner_code', $ownerCode)->where('scheduling_code', $scheduleCode)->first();
    }
    
    public function create(array $data) 
    {
        $schedule = DB::transaction(function() use ($data) {
            $ownerCode = $this->getOwnerCode($data['siteName']);
            $maxCode = Schedule::where('owner_code', $ownerCode)->max('scheduling_code');
            $attendant = Attendant::where('owner_code', $ownerCode)->where('attendant_code', $data['attendantCode'])->first();
            $service = Servicee::where('owner_code', $ownerCode)->where('service_code', $data['serviceCode'])->first();

            return Schedule::create([
                'scheduling_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $ownerCode,
                'attendant_code' => $attendant->attendant_code, 
                'attendant' => $attendant->name,
                'service_code' => $service->service_code,
                'service' => $service->name,
                'service_price' => $service->price,
                'customer_name' => $data['customerName'],
                'customer_phone' => $data['customerPhone'],
                'day' => $data['date'],
                'hour' => $data['hour'],
                'month' => $data['month'],
            ]);
        }); 

        return $schedule;
    }

    public function createCommissionRegister(int $ownerCode, int $attendantCode, int $scheduleCode, float $totalCommission)
    {
        $commission = DB::transaction(function() use ($ownerCode, $attendantCode, $scheduleCode, $totalCommission) {
            $maxCode = Commissions::where('owner_code', $ownerCode)->where('attendant_code', $attendantCode)->max('commission_code');
            $attendant = Attendant::where('owner_code', $ownerCode)->where('attendant_code', $attendantCode)->first();
            $schedule = Schedule::where('owner_code', $ownerCode)->where('scheduling_code', $scheduleCode)->first();
            $service = Servicee::where('owner_code', $ownerCode)->where('service_code', $schedule->service_code)->first();
            
            Log::info($service);

            return Commissions::create([
                'commission_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $ownerCode,
                'attendant_code' => $attendant->attendant_code,
                'attendant' => $attendant->name,
                'service_code' => $service->service_code,
                'service' => $service->name,
                'service_price' => $service->price,
                'category_code' => $service->category_code,
                'category' => $service->category,
                'scheduling_code' => $scheduleCode,
                'total_commission' => $totalCommission,
            ]); 
        });

        Log::info('Comissão criada com sucesso! line 90');
        return $commission;
    }

    public function checkCommission(int $ownerCode, int $attendantCode, int $serviceCode, object $schedule)
    {
        Log::debug('Começou o commission');
        $commission = DB::transaction(function() use ($ownerCode, $attendantCode, $serviceCode, $schedule) {
            $totalSchedule = $schedule->service_price;
            
            $commissionService = CommissionAttendant::where('owner_code', $ownerCode)->where('attendant_code', $attendantCode)->where('service_code', $serviceCode)->first();
            if(!$commissionService)
            {
                Log::warning("Não tem comissão para esse produto: serviceCode: {$serviceCode}");
                return;
            } else {
                Log::warning("Não tem comissão para esse produto: serviceCode: {$serviceCode}");
                Log::warning("Buscando o produto para ver se possui categoria, se possuir categoria, conferir se possui comissão por categoria: serviceCode: {$serviceCode}");
                $service = Servicee::where('owner_code', $ownerCode)->where('service_code', $serviceCode)->first();

                Log::debug('Serviço');
                Log::debug($service);

                if(!$service)
                {
                    Log::alert('Serviço não encontrado');
                    return;
                } else {
                    $commissionCategory = CommissionAttendant::where('owner_code', $ownerCode)->where('category_code', $service->category_code)->first();

                    if(!$commissionCategory) 
                    {
                        Log::alert('Não tem comissão');
                        return;

                    } else {
                        Log::debug('Tem comissão por categoria');
                        Log::debug($commissionCategory);
                        if($commissionCategory->fixed_commission)
                        {
                            Log::debug('É comissão por valor fixo');
                            Log::debug("R$ {$commissionCategory->fixed_commission}");
                            Log::debug("Total do agendamento serviços R$ {$totalSchedule}");
                            
                            $totalCommission = $totalSchedule - $commissionCategory->fixed_commission;
                            $commissionRegister = $this->createCommissionRegister($ownerCode, $attendantCode, $schedule->scheduling_code, $totalCommission);
                            Log::debug($commissionRegister);
                            return $commissionRegister;
                        } else {
                            Log::debug('É comissão por valor %');
                            Log::debug("% {$commissionCategory->perc_commission}");
                            Log::debug("Total do agendamento serviços R$ {$totalSchedule}");

                            $totalCommission = $totalSchedule * ($commissionCategory->perc_commission / 100);

                            Log::debug("Total do comissão dos serviços R$ {$totalCommission}");

                            $commissionRegister = $this->createCommissionRegister($ownerCode, $attendantCode, $schedule->scheduling_code, $totalCommission);
                            Log::debug($commissionRegister);
                            return $commissionRegister;
                        }

                        return;
                    }
                }
            }
        });

        Log::info('Comissão criada com sucesso! line 157');
        return $commission;
    }

    public function invoicingRegister(int $ownerCode, int $attendantCode, int $schedulingCode, float $inputValue)
    {
        // 'invoicing_code',
        // 'owner_code',
        // 'attendant_code',
        // 'scheduling_code',
        // 'input_value'



    }

    public function finish(array $data)
    {
        Log::debug('In finish');
        Log::debug($data);

        $finishedSchedule = DB::transaction(function() use ($data){
            $schedule = Schedule::where('owner_code', $data['ownerCode'])->where('attendant_code', $data['attendantCode'])->first();

            if(!$schedule)
            {
                Log::debug('Atendimento não encontrado');
            };

            $schedule->update([
                'completed' => 1
            ]);

            $this->checkCommission($data['ownerCode'],$data['attendantCode'], $data['serviceCode'], $schedule);

            return $schedule;
        });

        Log::info($finishedSchedule);
        Log::info('Agendamento finalizado com sucesso! line 184');

        return $finishedSchedule;
    }

    public function cancel(int $ownerCode, int $attendantCode)
    {
        $canceledSchedule = DB::transaction(function() use ($ownerCode, $attendantCode){
            $schedule = Schedule::where('owner_code', $ownerCode)->where('attendant_code', $attendantCode)->first();

            if(!$schedule) return;

            $schedule->update([
                'canceled' => 1
            ]);
        });

        return $canceledSchedule;
    }
}
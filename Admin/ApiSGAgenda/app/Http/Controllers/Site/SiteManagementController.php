<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\CreateURLSiteRequest;
use App\Http\Requests\Site\SiteSettingsRequest;
use App\Services\URLService\URLService;
use Illuminate\Support\Facades\Log;

class SiteManagementController extends Controller
{
    public function __construct(
        protected URLService $urlService
    ){}

    public function getURL(int $onwerCode)
    {
        return apiSuccess('URL encontrado com sucesso!', $this->urlService->getURL($onwerCode));
    }

    public function createURL(CreateURLSiteRequest $request)
    {
        $data = $request->validated();
        Log::info('-- SiteController --');
        Log::info($data);
        return apiSuccess('Site registrado com sucesso!', $this->urlService->createURL($data['url'], $data['urlName'], $data['ownerCode']));

    }

    public function getCategories(string $siteName)
    {
        return apiSuccess('Retornando todos as categorias', $this->urlService->getCategories($siteName));
    }

    public function getServices(string $siteName)
    {
        return apiSuccess('Retornando todos os serviços', $this->urlService->getServices($siteName));
    }

    public function findServiceByID(string $siteName, int $serviceCode)
    {
        return apiSuccess('Retornando o serviço selecionado', $this->urlService->findServiceByID($siteName, $serviceCode));
    }

    public function getAttendants(string $siteName)
    {
        return apiSuccess('Retornando os atendentes!', $this->urlService->getAttendants($siteName));
    }

    public function getAttendantHour(string $siteName, int $attendantCode)
    {
        return apiSuccess('Retornando os horários do atendente!', $this->urlService->getAttendantHour($siteName, $attendantCode));
    }

    public function saveSiteSettings(SiteSettingsRequest $request)
    {
        Log::debug($request->all());
        return apiSuccess('Retornando os horários do atendente!', $this->urlService->saveSiteSettings($request->validated()));
    }

    public function returnSiteSettings(string $siteName)
    {
        return apiSuccess('Retornando as configurações do site!', $this->urlService->returnSiteSettings($siteName));
    }
}

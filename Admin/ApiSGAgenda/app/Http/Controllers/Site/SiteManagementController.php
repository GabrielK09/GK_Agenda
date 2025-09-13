<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\CreateURLSiteRequest;
use App\Services\URLService\URLService;
use Illuminate\Support\Facades\Log;

class SiteManagementController extends Controller
{
    public function __construct(
        protected URLService $uRLService
    ){}

    public function getURL(int $onwerCode)
    {
        return apiSuccess('URL encontrado com sucesso!', $this->uRLService->getURL($onwerCode));
    }

    public function createURL(CreateURLSiteRequest $request)
    {
        $data = $request->validated();
        Log::info('-- SiteController --');
        Log::info($data);
        return apiSuccess('Site registrado com sucesso!', $this->uRLService->createURL($data['url'], $data['urlName'], $data['ownerCode']));

    }

    public function getCategories(string $siteName)
    {
        return apiSuccess('Retornando todos as categorias', $this->uRLService->getCategories($siteName));
    }

    public function getServices(string $siteName)
    {
        return apiSuccess('Retornando todos os serviços', $this->uRLService->getServices($siteName));
    }
}

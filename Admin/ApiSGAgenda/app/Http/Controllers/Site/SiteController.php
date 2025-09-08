<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\CreateURLSiteRequest;
use App\Services\URLService\URLService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SiteController extends Controller
{
    public function __construct(
        protected URLService $uRLService
    ){}

    public function createURL(CreateURLSiteRequest $request)
    {
        $data = $request->validated();
        Log::info('-- SiteController --');
        Log::info($data);
        return apiSuccess('Site registrado com sucesso!', $this->uRLService->createURL($data['url'], $data['urlName'], $data['ownerCode']));

    }
}

<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Services\CategoriesManagementService\CategoriesManagementService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoriesManagementService $categoriesManagementService
    ){}

    public function getAll(int $ownerCode)
    {
        return apiSuccess('', $this->categoriesManagementService->getAll($ownerCode));
    }

    public function getAllNotHasCommission(int $ownerCode)
    {
        return apiSuccess('', $this->categoriesManagementService->getAllNotHasCommission($ownerCode));
    }

    public function create(CategoryRequest $request)
    {
        return apiSuccess('Categoria cadastrada com sucesso!', $this->categoriesManagementService->create($request->validated()));
    }

    public function active(int $ownerCode, int $categoryCode)
    {
        return apiSuccess('Categoria ativada com sucesso!', $this->categoriesManagementService->active($ownerCode, $categoryCode));
    }

    public function delete(int $ownerCode, int $categoryCode)
    {
        return apiSuccess('Categoria desativada com sucesso!', $this->categoriesManagementService->disable($ownerCode, $categoryCode));
    }
}

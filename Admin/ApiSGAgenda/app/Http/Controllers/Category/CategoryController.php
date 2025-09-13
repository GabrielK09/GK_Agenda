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
        return apiSuccess('Categoria cadastrada com sucesso!', $this->categoriesManagementService->getAll($ownerCode));
    }

    public function create(CategoryRequest $request)
    {
        return apiSuccess('Categoria cadastrada com sucesso!', $this->categoriesManagementService->create($request->validated()));
    }
}

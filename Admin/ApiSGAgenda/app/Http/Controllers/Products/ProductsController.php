<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Services\ProductsManagementService\ProductsManagementService;

class ProductsController extends Controller
{
    public function __construct(
        protected ProductsManagementService $productsManagementService 
    ){}

    public function getAll(int $ownerCode)
    {
        return apiSuccess('Todos os produtos!', $this->productsManagementService->getAll($ownerCode));
    }

    public function create(ProductRequest $request)
    {
        return apiSuccess('Produto criado com sucesso!', $this->productsManagementService->create($request->validated()));
    }

    public function findByID(int $ownerCode, int $productCode)
    {
        return apiSuccess('Produto localizado com sucesso!', $this->productsManagementService->findByID($ownerCode, $productCode));
    }

    public function update(ProductRequest $request, int $ownerCode, int $productCode)
    {
        return apiSuccess('Produto alterado com sucesso!', $this->productsManagementService->update($request->validated(), $ownerCode, $productCode));
    }

    public function delete(int $ownerCode, int $productCode)
    {
        return apiSuccess('Produto deletado com sucesso!', $this->productsManagementService->delete($ownerCode, $productCode));
    }

    public function active(int $ownerCode, int $productCode)
    {
        return apiSuccess('Produto ativado com sucesso!', $this->productsManagementService->active($ownerCode, $productCode));
    }
}

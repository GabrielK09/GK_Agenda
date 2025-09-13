<?php

namespace App\Services\ProductsManagementService;

use App\Repositories\Eloquent\ProductsManagementEloquent\ProductsManagementRepository;
use Exception;

class ProductsManagementService 
{
    public function __construct(
        protected ProductsManagementRepository $productsManagementRepository
    ){}

    public function getAll(int $id)
    {           
        $allProducts = $this->productsManagementRepository->getAll($id);
        
        if(!$allProducts)
        {
            throw new Exception("Erro ao localizar todos os produtos!", 1);
        }

        return $allProducts;
    }  

    public function create(array $data)
    {
        $product = $this->productsManagementRepository->create($data);

        if(!$product)
        {
            throw new Exception("Erro ao cadastrar o produto", 1);
        }

        return $product;
    }

    public function findByID(int $ownerCode, int $productCode)
    {
        $product = $this->productsManagementRepository->findByID($ownerCode, $productCode);

        if(!$product)
        {
            throw new Exception("Erro ao localizar o produto: {$product}", 1);
        };

        return $product;        

    }

    public function update(array $data, int $ownerCode, int $productCode)
    {
        $product = $this->productsManagementRepository->update($data, $ownerCode, $productCode);

        if(!$product)
        {
            throw new Exception("Erro ao alterar o produto: {$product}", 1);
        };

        return $product;
    }

    public function delete(int $ownerCode, int $productCode)
    {
        $product = $this->productsManagementRepository->delete($ownerCode, $productCode);

        if(!$product)
        {
            throw new Exception("Erro ao deletar o produto: {$product}", 1);
        };

        return $product;
    }

    public function active(int $ownerCode, int $productCode)
    {
        $product = $this->productsManagementRepository->active($ownerCode, $productCode);

        if(!$product)
        {
            throw new Exception("Erro ao ativar o produto: {$product}", 1);
        };

        return $product;
    }
}
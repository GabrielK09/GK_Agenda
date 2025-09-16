<?php

namespace App\Services\CategoriesManagementService;

use App\Repositories\Eloquent\CategoriesManagementEloquent\CategoriesManagementRepository;
use Exception;

class CategoriesManagementService 
{
    public function __construct(
        protected CategoriesManagementRepository $categoriesManagementRepository
    ){}

    public function getAll(int $id)
    {           
        $allCategories = $this->categoriesManagementRepository->getAll($id);
        
        if(!$allCategories)
        {
            throw new Exception("Erro ao localizar todos os categorias!", 1);
        }

        return $allCategories;
    }  

    public function getAllNotHasCommission(int $id)
    {
        $allCategories = $this->categoriesManagementRepository->getAllNotHasCommission($id);
        
        return $allCategories;

    }

    public function create(array $data)
    {
        $category = $this->categoriesManagementRepository->create($data);

        if(!$category)
        {
            throw new Exception("Erro ao cadastrar a categoria", 1);
        }

        return $category;
    }

    public function findByID(int $ownerCode, int $categoryCode)
    {
        $category = $this->categoriesManagementRepository->findByID($ownerCode, $categoryCode);

        if(!$category)
        {
            throw new Exception("Erro ao buscar a categoria: {$category}", 1);
        };

        return $category;
    }

    public function update(array $data, int $ownerCode, int $categoryCode)
    {
        $category = $this->categoriesManagementRepository->update($data, $ownerCode, $categoryCode);

        if(!$category)
        {
            throw new Exception("Erro ao alterar a categoria: {$category}", 1);
        };

        return $category;
    }

    public function active(int $ownerCode, int $categoryCode)
    {
        $category = $this->categoriesManagementRepository->active($ownerCode, $categoryCode);

        if(!$category)
        {
            throw new Exception("Erro ao ativar a categoria: {$category}", 1);
        };

        return $category;
    }

    public function disable(int $ownerCode, int $categoryCode)
    {
        $category = $this->categoriesManagementRepository->disable($ownerCode, $categoryCode);

        if(!$category)
        {
            throw new Exception("Erro ao desabilitar a categoria: {$category}", 1);
        };

        return $category;
    }
}
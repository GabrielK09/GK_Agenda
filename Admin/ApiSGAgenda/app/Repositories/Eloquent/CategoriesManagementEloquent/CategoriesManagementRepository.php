<?php


namespace App\Repositories\Eloquent\CategoriesManagementEloquent;

use App\Models\Category;
use App\Models\CommissionAttendant;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoriesManagementRepository 
{
    public function getAll(int $id)
    {
        $categories = Category::where('owner_code', $id)->get();

        return $categories;

    }

    public function getAllNotHasCommission(int $id)
    {
        $allCategories = Category::where('owner_code', $id)->get();
        $newCategories = [];
        foreach ($allCategories as $category) {
            Log::debug('Dentro do foreach');
            Log::debug($category);
            $commissionCategory = CommissionAttendant::where('owner_code', $id)
                                                        ->where('category_code', $category->category_code)
                                                        ->first();

            if($commissionCategory) 
            {
                continue;
            } else {
                array_push($newCategories, $category);
            };
        }

        Log::debug('Fora do foreach');
        Log::debug($newCategories);

        return $newCategories;
    }


    public function create(array $data) 
    {
        Log::info('Dados para o create');
        Log::info($data);

        $id = DB::transaction(function() use ($data) {
            $parentCategory = null;
            if($data['parentCategory'])
            {
                Log::warning('Tem uma categoria pai');
                return $parentCategory = Category::where('owner_code', $data['ownerCode'])
                                            ->where('category_code', $data['categoryCode'])
                                            ->first();
            } else {
                Log::warning('Não tem uma categoria pai');
                return;    
            }

            Log::info($parentCategory); 

            $maxCode = Category::max('category_code');

            return Category::create([
                'category_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $data['ownerCode'],
                'parent_id' => $parentCategory->category_code ?? null,
                'parent_category' => $parentCategory->name ?? null,                
                'name' => $data['name'],
                'description' => $data['description'],
                
            ]);
        });

        Log::info($id);

        return $id;
    }

    public function findByID(int $ownerCode, int $categoryCode)
    {
        $category = Category::where('active', 1)
                            ->where('owner_code', $ownerCode)
                            ->where('category_code', $categoryCode)
                            ->first();

        if(!$category)
        {
            Log::warning("Categoria não encontado!, owner_code: {$ownerCode} | category_code: {$categoryCode}");
            return;
        }

        Log::info($category);
        
        return $category;
    }   


    public function update(array $data, int $ownerCode, int $categoryCode)
    {
        $id = DB::transaction(function() use ($data, $ownerCode, $categoryCode) {
            $service = $this->findByID($ownerCode, $categoryCode);
            $category = Category::where('category_code', $data['categoryCode'])
                                  ->where('owner_code', $data['ownerCode'])
                                  ->first();

            Log::alert('update service');
            Log::alert($service);

            if(!$service) return;
            
            $service->update([
                'category_code' => $category->category_code ?? null,
                'category' => $category->name ?? null,
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
                'duration_string' => $data['durationString'],
                'duration' => $data['duration'],
                'is_home_service' => $data['isHomeService'],
                'check_availability' => $data['checkAvailability'],

            ]);

            return $service;
        });

        return $id;
    }
}
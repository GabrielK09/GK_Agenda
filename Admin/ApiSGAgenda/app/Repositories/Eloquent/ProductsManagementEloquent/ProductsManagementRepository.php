<?php


namespace App\Repositories\Eloquent\ProductsManagementEloquent;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductsManagementRepository 
{
    public function getAll(int $id)
    {
        $products = Product::where('owner_code', $id)->get();

        return $products;

    }

    public function create(array $data) 
    {
        Log::info('Dados para o create');
        Log::info($data);

        $id = DB::transaction(function() use ($data) {
            $maxCode = Product::where('owner_code', $data['ownerCode'])->max('product_code');

            return Product::create([
                'product_code' => $maxCode ? $maxCode + 1 : 1,
                'owner_code' => $data['ownerCode'],
                'name' => $data['name'],
                'price' => $data['price'],
                'amount' => $data['amount']
                
            ]);
        });

        Log::info($id);

        return $id;
    }

    public function findByID(int $ownerCode, int $productCode)
    {
        $product = Product::where('active', 1)
                            ->where('owner_code', $ownerCode)
                            ->where('product_code', $productCode)
                            ->first();

        if(!$product)
        {
            Log::warning("Produto não encontado!, owner_code: {$ownerCode} | product_code: {$productCode}");
            return;
        }

        Log::info($product);
        
        return $product;
    }   


    public function update(array $data, int $ownerCode, int $productCode)
    {
        $id = DB::transaction(function() use ($data, $ownerCode, $productCode) {
            $product = $this->findByID($ownerCode, $productCode);

            Log::alert('update product');
            Log::alert($product);

            if(!$product) return;
            
            $product->update([
                'name' => $data['name'],
                'price' => $data['price'],
                'amount' => $data['amount']
            ]);

            return $product;
        });

        return $id;
    }

    public function delete(int $ownerCode, int $productCode)
    {  
        $id = DB::transaction(function() use ($ownerCode, $productCode) {
            $product = $this->findByID($ownerCode, $productCode);
            
            if(!$product) return;

            return $product->update([
                'active' => 0
            ]);
        });

        return $id;
    }


    public function active(int $ownerCode, int $productCode)
    {  
        $id = DB::transaction(function() use ($ownerCode, $productCode) {
            $product = Product::where('owner_code', $ownerCode)
                            ->where('product_code', $productCode)
                            ->first();
            
            if(!$product) return;

            return $product->update([
                'active' => 1
            ]);
        });

        return $id;
    }
}
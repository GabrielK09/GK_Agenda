<?php

use App\Http\Controllers\Attendant\AttendantController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Commission\CommissionController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Site\ServicesManagementController;
use App\Http\Controllers\Site\SiteManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function() {
    Route::prefix('/auth')->group(function() {
        Route::post('/register-owner', [AuthController::class, 'firstRegisterOwner']);
        Route::put('/update-owner/{id}', [OwnerController::class, 'update']);
        Route::post('/login', [AuthController::class, 'auth']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
    });

    Route::prefix('/site')->group(function() {
        Route::post('/create-url', [SiteManagementController::class, 'createURL']);

    });

    Route::middleware('auth:sanctum')->group(function() {
        Route::prefix('/services')->group(function() {
            Route::get('/all/{id}', [ServicesManagementController::class, 'getAll']); 
            Route::get('/all/not-commission/{id}', [ServicesManagementController::class, 'getAllNotHasCommission']); 
            Route::get('/find/{owner_code}/{service_code}', [ServicesManagementController::class, 'findByID']); 
            Route::put('/update/{owner_code}/{service_code}', [ServicesManagementController::class, 'update']); 
            Route::delete('/delete/{owner_code}/{service_code}', [ServicesManagementController::class, 'delete']); 
            Route::post('/create', [ServicesManagementController::class, 'create']); 
            Route::put('/active/{owner_code}/{product_code}', [ServicesManagementController::class, 'active']); 

        });

        Route::prefix('/categories')->group(function() {
            Route::get('/all/{id}', [CategoryController::class, 'getAll']); 
            Route::get('/all/not-commission/{id}', [CategoryController::class, 'getAllNotHasCommission']); 
            Route::get('/find/{owner_code}/{category_code}', [CategoryController::class, 'findByID']); 
            Route::put('/update/{owner_code}/{category_code}', [CategoryController::class, 'update']); 
            Route::delete('/delete/{owner_code}/{category_code}', [CategoryController::class, 'delete']); 
            Route::post('/create', [CategoryController::class, 'create']); 
            Route::put('/active/{owner_code}/{product_code}', [CategoryController::class, 'active']); 
        });
        
        Route::prefix('/attendants')->group(function() {
            Route::get('/all/{id}', [AttendantController::class, 'getAll']); 
            Route::post('/create', [AttendantController::class, 'create']); 
            Route::get('/find/{owner_code}/{attendant_code}', [AttendantController::class, 'findByID']); 
            Route::put('/update/{owner_code}/{attendant_code}', [AttendantController::class, 'update']); 
            Route::delete('/delete/{owner_code}/{attendant_code}', [AttendantController::class, 'delete']); 
            Route::put('/active/{owner_code}/{product_code}', [AttendantController::class, 'active']); 
        });

        Route::prefix('/products')->group(function() {
            Route::get('/all/{id}', [ProductsController::class, 'getAll']); 
            Route::post('/create', [ProductsController::class, 'create']); 
            Route::get('/find/{owner_code}/{product_code}', [ProductsController::class, 'findByID']); 
            Route::delete('/delete/{owner_code}/{product_code}', [ProductsController::class, 'delete']); 
            Route::put('/update/{owner_code}/{product_code}', [ProductsController::class, 'update']); 
            Route::put('/active/{owner_code}/{product_code}', [ProductsController::class, 'active']); 
        });

        Route::prefix('/commission')->group(function() {
            Route::get('/all/{owner_code}/{attendant_code}', [CommissionController::class, 'getAll']);
            Route::post('/create', [CommissionController::class, 'create']);
        });
    });
});
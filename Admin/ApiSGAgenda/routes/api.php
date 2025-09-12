<?php

use App\Http\Controllers\Attendant\AttendantController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Commission\CommissionController;
use App\Http\Controllers\Owner\OwnerController;
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
            Route::get('/find/{owner_code}/{service_code}', [ServicesManagementController::class, 'findByID']); 
            Route::put('/update/{owner_code}/{service_code}', [ServicesManagementController::class, 'update']); 
            Route::delete('/delete/{owner_code}/{service_code}', [ServicesManagementController::class, 'delete ']); 
            Route::post('/create', [ServicesManagementController::class, 'create']); 
        });
        
        Route::prefix('/attendants')->group(function() {
            Route::get('/all/{id}', [AttendantController::class, 'getAll']); 
            Route::post('/create', [AttendantController::class, 'create']); 
            Route::get('/find/{owner_code}/{service_code}', [AttendantController::class, 'findByID']); 
            Route::put('/update/{owner_code}/{service_code}', [AttendantController::class, 'update']); 
            Route::delete('/delete/{owner_code}/{service_code}', [AttendantController::class, 'delete ']); 
        });

        Route::prefix('/commission')->group(function() {
            Route::post('/create', [CommissionController::class, 'create']);
        });
    });
});
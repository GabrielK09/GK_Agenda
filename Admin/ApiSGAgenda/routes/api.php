<?php

use App\Http\Controllers\Attendant\AttendantController;
use App\Http\Controllers\Attendant\AttendantHoursController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Commission\CommissionController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Schedule\ScheduleController;
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
        Route::post('/create/schedule', [ScheduleController::class, 'create']);
        Route::get('/get-all/schedule/{siteName}', [ScheduleController::class, 'getAllBySite']);
        Route::get('/get-categories/{siteName}', [SiteManagementController::class, 'getCategories']);
        Route::get('/get-services/{siteName}', [SiteManagementController::class, 'getServices']);
        Route::get('/find/{site_name}/{service_code}', [SiteManagementController::class, 'findServiceByID']); 
        Route::get('/get-attendants/{siteName}', [SiteManagementController::class, 'getAttendants']);
        Route::get('/get-attendants/hours/{siteName}/{attendantCode}', [SiteManagementController::class, 'getAttendantHour']);

    });

    Route::middleware('auth:sanctum')->group(function() {
        Route::prefix('dashboard')->group(function() {
            Route::get('/dashboard/get-group-services/{owner_code}', [ServicesManagementController::class, 'groupServices']);

        });

        Route::prefix('/owner')->group(function() {
            Route::get('/data/{owner_code}', [OwnerController::class, 'findByID']);
            Route::put('/update/pix-key/{owner_code}', [OwnerController::class, 'pixKey']);

        });

        Route::prefix('/services')->group(function() {
            Route::get('/all/{id}', [ServicesManagementController::class, 'getAll']); 
            Route::get('/all/not-commission/{id}', [ServicesManagementController::class, 'getAllNotHasCommission']); 
            Route::get('/find/{owner_code}/{service_code}', [ServicesManagementController::class, 'findByID']); 
            Route::put('/update/{owner_code}/{service_code}', [ServicesManagementController::class, 'update']); 
            Route::post('/create', [ServicesManagementController::class, 'create']); 
            Route::put('/active/{owner_code}/{product_code}', [ServicesManagementController::class, 'active']); 
            Route::delete('/delete/{owner_code}/{service_code}', [ServicesManagementController::class, 'delete']); 

        });

        Route::prefix('/categories')->group(function() {
            Route::get('/all/{id}', [CategoryController::class, 'getAll']); 
            Route::get('/all/not-commission/{id}', [CategoryController::class, 'getAllNotHasCommission']); 
            Route::get('/find/{owner_code}/{category_code}', [CategoryController::class, 'findByID']); 
            Route::put('/update/{owner_code}/{category_code}', [CategoryController::class, 'update']); 
            Route::post('/create', [CategoryController::class, 'create']); 
            Route::put('/active/{owner_code}/{product_code}', [CategoryController::class, 'active']); 
            Route::delete('/delete/{owner_code}/{category_code}', [CategoryController::class, 'delete']); 

        });
        
        Route::prefix('/attendants')->group(function() {
            Route::get('/all/{id}', [AttendantController::class, 'getAll']); 
            Route::post('/create', [AttendantController::class, 'create']); 
            Route::get('/find/{owner_code}/{attendant_code}', [AttendantController::class, 'findByID']); 
            Route::put('/update/{owner_code}/{attendant_code}', [AttendantController::class, 'update']); 
            Route::delete('/delete/{owner_code}/{attendant_code}', [AttendantController::class, 'delete']); 
            Route::put('/active/{owner_code}/{product_code}', [AttendantController::class, 'active']); 
            Route::post('/create/hours', [AttendantHoursController::class, 'create']);
            Route::get('/get/hours/{owner_code}/{attendant_code}', [AttendantHoursController::class, 'getHours']);
            
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

        Route::prefix('/site')->group(function() {
            Route::get('/get-url/{owner_code}', [SiteManagementController::class, 'getURL']);
            Route::post('/save-site-settings', [SiteManagementController::class, 'saveSiteSettings']);
        });

        Route::prefix('/schedule')->group(function() {
            Route::get('/get-all/{owner_code}', [ScheduleController::class, 'getAll']);
            Route::get('/get-detail/{owner_code}/{schedule_code}', [ScheduleController::class, 'detail']);
            Route::put('/finish/schedule', [ScheduleController::class, 'finish']);
        }); 
    });
});
<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function() {
    Route::prefix('/auth')->group(function() {
        Route::post('/register-owner', [AuthController::class, 'firstRegisterOwner']);
        Route::put('/update-owner/{id}', [OwnerController::class, 'update']);
        Route::post('/login', [AuthController::class, 'auth']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
    });

    Route::prefix('/site')->group(function() {
        Route::post('/create-url', [SiteController::class, 'createURL']);

    });

    Route::middleware('auth:sanctum')->group(function() {
    });
});
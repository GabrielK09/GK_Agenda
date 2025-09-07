<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Owner\OwnerController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function() {
    Route::prefix('/auth')->group(function() {
        Route::post('/register-owner', [AuthController::class, 'firstRegisterOwner']);
        Route::put('/update-owner/{id}', [OwnerController::class, 'update']);
        Route::post('/login', [AuthController::class, 'auth']);
    });

    Route::middleware('auth:sanctum')->group(function() {
    });
});
<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function() {
    Route::prefix('/auth')->group(function() {
        Route::post('/register-owner', [AuthController::class, 'registerOwner']);
    });

    Route::middleware('sanctum')->group(function() {
        Route::get('/login', function () {
            
        });
    });
});
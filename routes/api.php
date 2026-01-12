
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Support\Facades\Route;

// Public routes
// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{id}', [OrderController::class, 'show']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::middleware(\App\Http\Middleware\CheckUserRole::class . ':admin')->group(function () {
        Route::post('/stores', [StoreController::class, 'store']);
    });
    
    Route::get('/store/orders', [StoreController::class, 'orders']);
});
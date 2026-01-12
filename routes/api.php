
<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{id}', [OrderController::class, 'show']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/store/orders', [StoreController::class, 'orders']);
});
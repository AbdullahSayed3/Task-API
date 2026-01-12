<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Services\OrderService;
use App\Services\StoreService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    use ApiResponse;

    public function __construct(
        private StoreService $storeService,
        private OrderService $orderService
    ) {}

    /**
     * Get orders for authenticated merchant's store
     */
    public function orders(Request $request)
    {
        $user = $request->user();
        
        // Get merchant's store
        $store = $this->storeService->getUserStore($user->id);

        if (!$store) {
            return $this->notFoundResponse('Store not found for this user');
        }

        // Get store orders
        $orders = $this->orderService->getStoreOrders($store->id);

        return $this->successResponse(
            OrderItemResource::collection($orders),
            'Store orders retrieved successfully'
        );
    }

    /**
     * Create a new store
     */
    public function store(\App\Http\Requests\StoreStoreRequest $request)
    {
        $store = $this->storeService->createStore($request->validated(), $request->user()->id);

        return $this->createdResponse(
            new \App\Http\Resources\StoreResource($store),
            'Store created successfully'
        );
    }
}
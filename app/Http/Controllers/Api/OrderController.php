<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Traits\ApiResponse;

class OrderController extends Controller
{
    use ApiResponse;

    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Create a new order
     */
    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->validated());

        return $this->createdResponse(
            new OrderResource($order),
            'Order created successfully'
        );
    }

    /**
     * Get order by ID
     */
    public function show(int $id)
    {
        $order = $this->orderService->getOrder($id);

        if (!$order) {
            return $this->notFoundResponse('Order not found');
        }

        return $this->successResponse(
            new OrderResource($order),
            'Order retrieved successfully'
        );
    }
}
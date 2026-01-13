<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Services\StoreService;
use App\Traits\ApiResponse;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        private ProductService $productService,
        private StoreService $storeService
    ) {}

    public function store(StoreProductRequest $request)
    {
        $user = $request->user();
        $store = $this->storeService->getUserStore($user->id);

        if (!$store) {
            return $this->errorResponse('You must create a store before adding products.', 400);
        }

        $product = $this->productService->createProduct($request->validated(), $store);

        return $this->createdResponse(
            new ProductResource($product),
            'Product created successfully'
        );
    }
}

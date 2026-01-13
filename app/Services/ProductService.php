<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Store;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function createProduct(array $data, Store $store): Product
    {
        $data['store_id'] = $store->id;
        return $this->productRepository->create($data);
    }
}

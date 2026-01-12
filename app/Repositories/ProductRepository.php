<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function getActiveByIds(array $productIds): Collection
    {
        return Product::whereIn('id', $productIds)
            ->where('is_active', true)
            ->get();
    }

    public function find(int $id): ?Product
    {
        return Product::find($id);
    }
}   
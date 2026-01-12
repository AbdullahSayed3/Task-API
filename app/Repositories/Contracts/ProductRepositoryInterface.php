<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function getActiveByIds(array $productIds): Collection;
    
    public function find(int $id): ?Product;
}
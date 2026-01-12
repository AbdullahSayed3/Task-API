<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function create(array $data): Order;
    
    public function getOrderItemsByStore(int $storeId): Collection;
    
    public function find(int $id): ?Order;
}
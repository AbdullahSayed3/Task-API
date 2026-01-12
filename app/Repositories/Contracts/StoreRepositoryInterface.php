<?php

namespace App\Repositories\Contracts;

use App\Models\Store;

interface StoreRepositoryInterface
{
    public function getByUserId(int $userId): ?Store;
    
    public function find(int $id): ?Store;

    public function create(array $data): Store;
}
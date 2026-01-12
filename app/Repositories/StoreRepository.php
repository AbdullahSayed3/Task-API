<?php

namespace App\Repositories;

use App\Models\Store;
use App\Repositories\Contracts\StoreRepositoryInterface;

class StoreRepository implements StoreRepositoryInterface
{
    public function getByUserId(int $userId): ?Store
    {
        return Store::where('user_id', $userId)->first();
    }

    public function find(int $id): ?Store
    {
        return Store::find($id);
    }

    public function create(array $data): Store
    {
        return Store::create($data);
    }
}
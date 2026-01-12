<?php

namespace App\Services;

use App\Models\Store;
use App\Repositories\Contracts\StoreRepositoryInterface;

class StoreService
{
    public function __construct(
        private StoreRepositoryInterface $storeRepository
    ) {}

    public function getUserStore(int $userId): ?Store
    {
        return $this->storeRepository->getByUserId($userId);
    }

    public function createStore(array $data, int $userId): Store
    {
        $data['user_id'] = $userId;
        return $this->storeRepository->create($data);
    }
}
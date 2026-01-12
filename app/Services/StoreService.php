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
}
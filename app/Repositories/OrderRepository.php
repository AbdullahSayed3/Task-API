<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // create order
            $order = Order::create(array_merge($data, ['status' => 'pending']));

            // add items
            foreach ($data['items'] as $item) {
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'store_id' => $item['store_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return $order->load('orderItems.product', 'orderItems.store');
        });
    }

    public function getOrderItemsByStore(int $storeId): Collection
    {
        return OrderItem::where('store_id', $storeId)
            ->with(['order', 'product'])
            ->latest()
            ->get();
    }

    public function find(int $id): ?Order
    {
        return Order::with('orderItems.product', 'orderItems.store')->find($id);
    }
}
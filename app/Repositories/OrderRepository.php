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
            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'total_amount' => $data['total_amount'],
                'status' => 'pending',
            ]);

            // add items
            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'store_id' => $item['store_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
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
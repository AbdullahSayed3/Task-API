<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderNotificationMail;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Create a new order and send emails to merchants
     */
    public function createOrder(array $data): Order
    {
        // 1. Validate products exist and are active
        $products = $this->validateAndGetProducts($data['items']);

        // 2. Calculate total and add store_id to items
        $orderData = $this->prepareOrderData($data, $products);

        // 3. Create the order
        $order = $this->orderRepository->create($orderData);

        // 4. Send emails to merchants
        $this->sendMerchantNotifications($order);

        return $order;
    }

    private function validateAndGetProducts(array $items): Collection
    {
        $productIds = collect($items)->pluck('product_id')->toArray();
        $products = $this->productRepository->getActiveByIds($productIds);

        if ($products->count() !== count($productIds)) {
            throw new \App\Exceptions\ProductNotAvailableException();
        }

        return $products;
    }

    private function prepareOrderData(array $data, Collection $products): array
    {
        $totalAmount = 0;
        $processedItems = [];

        foreach ($data['items'] as $item) {
            $product = $products->firstWhere('id', $item['product_id']);

            if (!$product) {
                // Should ideally be caught by validateAndGetProducts but safe to keep check
                throw new \App\Exceptions\ProductNotAvailableException($item['product_id']);
            }

            $itemTotal = $product->price * $item['quantity'];
            $totalAmount += $itemTotal;

            $processedItems[] = [
                'product_id' => $product->id,
                'store_id' => $product->store_id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];
        }

        return [
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'total_amount' => $totalAmount,
            'items' => $processedItems,
        ];
    }

    /**
     * Send email notifications to merchants
     */
    private function sendMerchantNotifications(Order $order): void
    {
        // Group items by store
        $itemsByStore = $order->orderItems->groupBy('store_id');

        foreach ($itemsByStore as $storeId => $items) {
            $store = $items->first()->store;
            
            // Send email to merchant
            Mail::to($store->user->email)->send(
                new OrderNotificationMail($order, $store, $items)
            );
        }
    }

// Get order items for a specific store
    public function getStoreOrders(int $storeId): Collection
    {
        return $this->orderRepository->getOrderItemsByStore($storeId);
    }

    // Get order by ID
     
    public function getOrder(int $orderId): ?Order
    {
        return $this->orderRepository->find($orderId);
    }
}
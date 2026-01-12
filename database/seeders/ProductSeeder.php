<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();

        if ($stores->isEmpty()) {
            $this->command->error('No stores found. Run StoreSeeder first!');
            return;
        }

        $products = [
            // Store 1 - Electronics
            [
                'store_id' => $stores[0]->id,
                'products' => [
                    ['name' => 'Laptop HP', 'description' => '15 inch display', 'price' => 899.99, 'stock' => 10],
                    ['name' => 'Wireless Mouse', 'description' => 'Bluetooth mouse', 'price' => 25.50, 'stock' => 50],
                    ['name' => 'USB Cable', 'description' => 'Type-C 2m', 'price' => 9.99, 'stock' => 100],
                ]
            ],
            // Store 2 - Fashion
            [
                'store_id' => $stores[1]->id,
                'products' => [
                    ['name' => 'T-Shirt', 'description' => 'Cotton black t-shirt', 'price' => 19.99, 'stock' => 30],
                    ['name' => 'Jeans', 'description' => 'Blue denim jeans', 'price' => 49.99, 'stock' => 20],
                    ['name' => 'Sneakers', 'description' => 'Sport shoes size 42', 'price' => 79.99, 'stock' => 15],
                ]
            ],
            // Store 3 - Grocery
            [
                'store_id' => $stores[2]->id,
                'products' => [
                    ['name' => 'Tomatoes', 'description' => 'Fresh red tomatoes 1kg', 'price' => 2.99, 'stock' => 100],
                    ['name' => 'Bananas', 'description' => 'Organic bananas 1kg', 'price' => 1.99, 'stock' => 80],
                    ['name' => 'Potatoes', 'description' => 'Fresh potatoes 2kg', 'price' => 3.49, 'stock' => 120],
                ]
            ],
        ];

        foreach ($products as $storeProducts) {
           foreach ($storeProducts['products'] as $product) {
            $product['store_id'] = $storeProducts['store_id'];
            Product::create($product);
        }
        }
    }
}
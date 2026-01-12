<?php

namespace Tests\Feature;

use App\Mail\OrderNotificationMail;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_separate_emails_to_vendors_with_only_their_products()
    {
        Mail::fake();

        // 1. Setup Vendors and Stores
        $merchantA = User::factory()->create(['role' => \App\Enums\UserRole::ADMIN->value, 'email' => 'merchantA@test.com']);
        $storeA = Store::factory()->create(['user_id' => $merchantA->id, 'name' => 'Store A']);

        $merchantB = User::factory()->create(['role' => \App\Enums\UserRole::ADMIN->value, 'email' => 'merchantB@test.com']);
        $storeB = Store::factory()->create(['user_id' => $merchantB->id, 'name' => 'Store B']);

        // 2. Setup Products
        $productA = Product::factory()->create([
            'store_id' => $storeA->id,
            'price' => 100,
            'is_active' => true
        ]);

        $productB = Product::factory()->create([
            'store_id' => $storeB->id,
            'price' => 200,
            'is_active' => true
        ]);

        // 3. Customer places order
        $payload = [
            'customer_name' => 'Mahmoud',
            'customer_email' => 'mahmoud@test.com',
            'customer_phone' => '1234567890',
            'items' => [
                [
                    'product_id' => $productA->id,
                    'quantity' => 2
                ],
                [
                    'product_id' => $productB->id,
                    'quantity' => 1
                ]
            ]
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->dump();
        $response->assertStatus(201);

        // 4. Assert Emails Sent
        Mail::assertSent(OrderNotificationMail::class, 2);

        // 5. Assert Content for Merchant A
        Mail::assertSent(OrderNotificationMail::class, function ($mail) use ($merchantA, $productA, $productB) {
            if ($mail->store->user->email !== $merchantA->email) {
                return false;
            }

            // Check if mail contains Product A
            $hasProductA = $mail->items->contains('product_id', $productA->id);
            // Check if mail DOES NOT contain Product B
            $hasProductB = $mail->items->contains('product_id', $productB->id);

            return $hasProductA && !$hasProductB;
        });

        // 6. Assert Content for Merchant B
        Mail::assertSent(OrderNotificationMail::class, function ($mail) use ($merchantB, $productA, $productB) {
            if ($mail->store->user->email !== $merchantB->email) {
                return false;
            }

            // Check if mail contains Product B
            $hasProductB = $mail->items->contains('product_id', $productB->id);
            // Check if mail DOES NOT contain Product A
            $hasProductA = $mail->items->contains('product_id', $productA->id);

            return $hasProductB && !$hasProductA;
        });
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_store()
    {
        $user = User::factory()->create(['role' => \App\Enums\UserRole::ADMIN->value]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/stores', [
            'name' => 'My New Store',
            'description' => 'Best store ever',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'My New Store',
                    'description' => 'Best store ever',
                ]
            ]);

        $this->assertDatabaseHas('stores', [
            'name' => 'My New Store',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_create_store_without_name()
    {
        $user = User::factory()->create(['role' => \App\Enums\UserRole::ADMIN->value]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/stores', [
            'description' => 'Best store ever',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => [
                    'name' => ['The name field is required.']
                ]
            ]);
    }

    public function test_regular_user_cannot_create_store()
    {
        $user = User::factory()->create(['role' => \App\Enums\UserRole::USER->value]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/stores', [
            'name' => 'My New Store',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(403);
    }
}

<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => UserRole::USER->value,
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'token'
                ]
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $payload = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'token'
                ]
            ]);
    }

    public function test_custom_token_format()
    {
        $user = User::factory()->create();
        
        // Login to get token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Factory default is 'password'
        ]);

        $token = $response->json('data.token');
        
        // Token format is id|random_string
        $parts = explode('|', $token);
        $this->assertCount(2, $parts);
        
        // Check if random string part is 400 chars long
        $this->assertEquals(400, strlen($parts[1]), 'Token length should be 400 characters');
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
            
        $this->assertCount(0, $user->tokens);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 merchants with stores
        $merchants = [
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed@example.com',
                'store_name' => 'Ahmed Electronics Store',
                'store_description' => 'Best electronics in town'
            ],
            [
                'name' => 'Mohamed Hassan',
                'email' => 'mohamed@example.com',
                'store_name' => 'Mohamed Fashion Store',
                'store_description' => 'Latest fashion trends'
            ],
            [
                'name' => 'Ali Mahmoud',
                'email' => 'ali@example.com',
                'store_name' => 'Ali Grocery Store',
                'store_description' => 'Fresh groceries daily'
            ],
        ];

        foreach ($merchants as $merchantData) {
            $user = User::create([
                'name' => $merchantData['name'],
                'email' => $merchantData['email'],
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]);

            Store::create([
                'user_id' => $user->id,
                'name' => $merchantData['store_name'],
                'description' => $merchantData['store_description'],
            ]);
        }
    }
}
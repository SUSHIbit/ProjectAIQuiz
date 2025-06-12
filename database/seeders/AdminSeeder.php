<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
            'tier' => 'premium',
            'question_attempts' => 999,
            'email_verified_at' => now(),
        ]);

        // Create some test users
        User::factory(10)->create([
            'role' => 'user',
            'tier' => 'free',
            'question_attempts' => 5, // Updated from 3 to 5
        ]);

        User::factory(5)->create([
            'role' => 'user',
            'tier' => 'premium',
            'question_attempts' => 999,
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin account
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'pw12345678',
            'role' => 'admin',
        ]);

        // Create user account
        User::factory()->create([
            'name' => 'user',
            'email' => 'user@test.com',
            'password' => 'pw12345678',
            'role' => 'user',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@bohrifarm.com',
            'phone' => '081234567890',
            'password' => Hash::make('B0hr!f@rm'),
            'role' => 'admin',
        ]);

        // User
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@bohrifarm.com',
            'phone' => '089876543210',
            'password' => Hash::make('B0hr!f@rm'),
            'role' => 'user',
        ]);
    }
}

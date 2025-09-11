<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@sampah.com',
                'password' => Hash::make('admin123'),
                'phone_number' => '081234567890',
                'address' => 'Jl. Admin No. 1',
                'role' => 'admin',
                'created_at' => now(),
            ],
            [
                'name' => 'User',
                'email' => 'user@sampah.com',
                'password' => Hash::make('user123'),
                'phone_number' => '081298765432',
                'address' => 'Jl. User No. 2',
                'role' => 'user',
                'created_at' => now(),
            ]
            

        ]);
    }
}

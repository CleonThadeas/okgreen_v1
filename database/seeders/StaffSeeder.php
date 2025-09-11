<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        Staff::updateOrCreate(
            ['email' => 'staff@example.com'], // pastikan kolom di DB memang 'email'
            [
                'name' => 'Default Staff',
                'email' => 'staff@example.com', // pastikan email terisi di update
                'password' => Hash::make('password123'), // hash password
            ]
        );
    }
}

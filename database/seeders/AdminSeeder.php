<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'andri@gmail.com'], // Cek kalau sudah ada
            [
                'name' => 'Super Admin',
                'email' => 'andri@gmail.com', // pastikan email terisi di update
                'password' => Hash::make('12345678'),
            ]
        );
    }
}

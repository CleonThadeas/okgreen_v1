<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tambah data user dummy
        \App\Models\User::factory(5)->create();

        // Seed admin & staff default
        $this->call([
            AdminSeeder::class,
            StaffSeeder::class,
        ]);
    }
}

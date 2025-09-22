<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // Seed admin & staff default
        $this->call([
            AdminSeeder::class,
            StaffSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\User;
=======
>>>>>>> 62fbded8ab217af7ea576d0b710c9ebe9be5e110
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
<<<<<<< HEAD

        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(UserSeeder::class);
=======
>>>>>>> 62fbded8ab217af7ea576d0b710c9ebe9be5e110
    }
}

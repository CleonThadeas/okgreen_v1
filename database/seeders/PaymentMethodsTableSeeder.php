<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('payment_methods')->insert([
            ['name'=>'QRIS',   'description'=>'Pembayaran via QRIS'],
            ['name'=>'Dana',   'description'=>'Pembayaran via DANA'],
            ['name'=>'OVO',    'description'=>'Pembayaran via OVO'],
            ['name'=>'GoPay',  'description'=>'Pembayaran via GoPay'],
            ['name'=>'ShopeePay','description'=>'Pembayaran via ShopeePay'],
        ]);
    }
}

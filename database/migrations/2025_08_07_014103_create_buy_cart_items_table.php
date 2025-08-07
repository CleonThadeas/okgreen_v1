<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buy_cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->foreignId('transaction_id')
                  ->constrained('buy_transactions', 'transaction_id')
                  ->onDelete('cascade');
            $table->foreignId('type_id')
                  ->constrained('waste_types', 'type_id')
                  ->onDelete('restrict');
            $table->integer('quantity')->unsigned();
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buy_cart_items');
    }
};

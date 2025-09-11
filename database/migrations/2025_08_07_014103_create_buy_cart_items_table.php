<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('buy_cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('buy_transaction_id')->constrained('buy_transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('waste_type_id')->constrained('waste_types')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedInteger('quantity');
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('buy_cart_items');
    }
};

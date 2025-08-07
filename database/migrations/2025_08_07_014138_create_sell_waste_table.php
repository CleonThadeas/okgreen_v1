<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sell_waste', function (Blueprint $table) {
            $table->id('sell_id');
            $table->foreignId('user_id')->constrained('users','user_id')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('waste_categories','category_id')->onDelete('restrict');
            $table->foreignId('type_id')->constrained('waste_types','type_id')->onDelete('restrict');
            $table->decimal('weight_kg', 10, 2);
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sell_waste');
    }
};

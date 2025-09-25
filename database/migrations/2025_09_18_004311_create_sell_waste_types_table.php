<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sell_waste_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('waste_category_id')
                  ->constrained('waste_categories')
                  ->onDelete('cascade');
            $table->string('type_name', 150);
            $table->text('description')->nullable();
            $table->decimal('points_per_kg', 10, 2)->default(0); // poin yang didapat user
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sell_waste_types');
    }
};

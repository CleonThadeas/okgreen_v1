<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sell_waste', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('waste_category_id')->constrained('waste_categories')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('waste_type_id')->constrained('waste_types')->onDelete('restrict')->onUpdate('cascade');
            $table->decimal('weight_kg', 10, 2);
            $table->decimal('price_per_kg', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('sell_method', ['drop_point','pickup'])->default('drop_point');
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable(); // legacy single-photo: tetap ada untuk backward compatibility
            $table->integer('points_awarded')->nullable();
            $table->timestamps();

            // index sederhana untuk performa lookup staff
            $table->index(['status','created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('sell_waste');
    }
};
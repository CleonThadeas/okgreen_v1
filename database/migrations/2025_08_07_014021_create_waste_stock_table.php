<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('waste_stock', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('waste_type_id')->constrained('waste_types')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('available_weight', 10, 2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('waste_stock');
    }
};

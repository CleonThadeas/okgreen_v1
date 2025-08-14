<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('waste_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('waste_category_id')->constrained('waste_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->string('type_name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('waste_types');
    }
};

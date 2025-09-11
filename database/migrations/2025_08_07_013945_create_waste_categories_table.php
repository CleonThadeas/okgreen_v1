<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name', 100);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('waste_categories');
    }
};

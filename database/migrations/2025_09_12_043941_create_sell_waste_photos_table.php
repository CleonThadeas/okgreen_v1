<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sell_waste_photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('sell_id')->constrained('sell_waste')->onDelete('cascade')->onUpdate('cascade');
            $table->string('photo_path', 255);
            $table->tinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sell_waste_photos');
    }
};

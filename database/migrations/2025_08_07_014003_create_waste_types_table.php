<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_types', function (Blueprint $table) {
            $table->id('type_id');
            $table->foreignId('category_id')
                  ->constrained('waste_categories', 'category_id')
                  ->onDelete('cascade');
            $table->string('type_name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_types');
    }
};

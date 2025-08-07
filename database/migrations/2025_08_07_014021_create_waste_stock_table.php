<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_stock', function (Blueprint $table) {
            $table->id('stock_id');
            $table->foreignId('type_id')
                  ->constrained('waste_types', 'type_id')
                  ->onDelete('cascade');
            $table->decimal('available_weight', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_stock');
    }
};

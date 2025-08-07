<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('point_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->foreignId('user_id')
                  ->constrained('users','user_id')
                  ->onDelete('cascade');
            $table->enum('source', ['penjualan','pembelian','penukaran','giveaway']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('points_change');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('point_history');
    }
};

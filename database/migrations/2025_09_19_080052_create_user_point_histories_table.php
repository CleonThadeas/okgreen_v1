<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('source'); // contoh: 'Transaksi', 'Reward', dll
            $table->unsignedBigInteger('reference_id')->nullable(); // ID transaksi/penjualan
            $table->integer('points_change'); // bisa negatif / positif
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_point_histories');
    }
};

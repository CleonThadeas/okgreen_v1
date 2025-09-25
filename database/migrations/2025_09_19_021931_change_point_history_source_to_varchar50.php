<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('point_history', function (Blueprint $table) {
            // ubah kolom source jadi varchar(50) (non-nullable atau nullable sesuai kebutuhan)
            $table->string('source', 50)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('point_history', function (Blueprint $table) {
            // kamu bisa kembalikan ke panjang semula; ganti sesuai definisi semula
            $table->string('source', 20)->change();
        });
    }
};

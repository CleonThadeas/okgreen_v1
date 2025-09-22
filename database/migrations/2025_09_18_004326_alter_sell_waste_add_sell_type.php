<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('sell_waste', function (Blueprint $table) {
            // hapus kolom lama kalau ada
            if (Schema::hasColumn('sell_waste', 'waste_type_id')) {
                $table->dropForeign(['waste_type_id']);
                $table->dropColumn('waste_type_id');
            }

            // tambahkan kolom baru
            $table->foreignId('sell_waste_type_id')
                  ->after('waste_category_id')
                  ->constrained('sell_waste_types')
                  ->onDelete('restrict');
        });
    }

    public function down(): void {
        Schema::table('sell_waste', function (Blueprint $table) {
            if (Schema::hasColumn('sell_waste', 'sell_waste_type_id')) {
                $table->dropForeign(['sell_waste_type_id']);
                $table->dropColumn('sell_waste_type_id');
            }

            $table->foreignId('waste_type_id')
                  ->constrained('waste_types')
                  ->onDelete('restrict');
        });
    }
};

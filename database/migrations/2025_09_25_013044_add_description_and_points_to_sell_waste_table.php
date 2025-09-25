<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('sell_waste', function (Blueprint $table) {
            if (!Schema::hasColumn('sell_waste', 'description')) {
                $table->text('description')->nullable()->after('sell_method');
            }
            if (!Schema::hasColumn('sell_waste', 'points_awarded')) {
                $table->integer('points_awarded')->default(0)->after('photo_path');
            }
        });
    }

    public function down(): void {
        Schema::table('sell_waste', function (Blueprint $table) {
            if (Schema::hasColumn('sell_waste', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('sell_waste', 'points_awarded')) {
                $table->dropColumn('points_awarded');
            }
        });
    }
};

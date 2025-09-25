<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sell_waste', function (Blueprint $table) {
            $table->unsignedBigInteger('handled_by_staff_id')->nullable()->after('points_awarded');
            $table->timestamp('handled_at')->nullable()->after('handled_by_staff_id');

            // kalau ada relasi staff:
            // $table->foreign('handled_by_staff_id')->references('id')->on('staff')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('sell_waste', function (Blueprint $table) {
            $table->dropColumn(['handled_by_staff_id', 'handled_at']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'receiver_role')) {
                $table->string('receiver_role', 20)->nullable();
            }

            if (!Schema::hasColumn('notifications', 'receiver_id')) {
                $table->unsignedBigInteger('receiver_id')->nullable();
            }

            $table->index(['receiver_role', 'receiver_id']);
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'receiver_role')) {
                $table->dropColumn('receiver_role');
            }

            if (Schema::hasColumn('notifications', 'receiver_id')) {
                $table->dropColumn('receiver_id');
            }
        });
    }
};

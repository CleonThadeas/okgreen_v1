<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasColumn('notifications', 'title')) {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('title')->nullable()->after('receiver_role');
        });
    }
}


public function down(): void
{
    Schema::table('notifications', function (Blueprint $table) {
        $table->dropColumn('title');
    });
}
};

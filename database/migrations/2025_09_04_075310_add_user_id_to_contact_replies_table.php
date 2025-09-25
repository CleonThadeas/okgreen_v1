<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            // Tambahkan kolom hanya jika belum ada
            if (!Schema::hasColumn('contact_replies', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('admin_id');
            }
        });
    }

    public function down()
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            // Hapus kolom hanya jika ada
            if (Schema::hasColumn('contact_replies', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};

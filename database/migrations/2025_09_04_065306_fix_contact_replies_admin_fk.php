<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            // Hapus foreign key lama jika ada
            $fkExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'contact_replies' 
                  AND COLUMN_NAME = 'admin_id' 
                  AND CONSTRAINT_SCHEMA = DATABASE()
            ");

            if (!empty($fkExists)) {
                $table->dropForeign(['admin_id']);
            }

            // Tambahkan foreign key baru ke admins.id
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
        });
    }
};

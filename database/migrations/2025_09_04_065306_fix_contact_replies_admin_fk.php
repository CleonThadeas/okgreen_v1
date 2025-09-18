<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            // Hapus foreign key lama (ke tabel users)
            $table->dropForeign(['admin_id']);

            // Tambah foreign key baru ke tabel admins
            $table->foreign('admin_id')
                  ->references('id')
                  ->on('admins')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            // Rollback: hapus fk ke admins
            $table->dropForeign(['admin_id']);

            // Kembalikan fk ke users
            $table->foreign('admin_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};

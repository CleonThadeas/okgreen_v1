<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            $table->enum('sender_type', ['user', 'admin'])->default('admin')->after('message_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            $table->dropColumn('sender_type');
        });
    }

};

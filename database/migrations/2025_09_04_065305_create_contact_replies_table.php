<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('contact_replies', function (Blueprint $table) {
            $table->id('reply_id');

            // FK ke contact_messages.message_id
            $table->unsignedBigInteger('message_id');
            $table->foreign('message_id')
                  ->references('message_id')
                  ->on('contact_messages')
                  ->onDelete('cascade');

            // sender type: admin / staff / user
            $table->enum('sender_type', ['admin', 'staff', 'user']);

            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();

            $table->text('reply');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_replies');
    }
};

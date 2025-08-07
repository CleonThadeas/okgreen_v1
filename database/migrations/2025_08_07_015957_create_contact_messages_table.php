<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->foreignId('user_id')
                  ->constrained('users','user_id')
                  ->onDelete('cascade');
            $table->string('subject', 255);
            $table->text('message');
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_messages');
    }
};

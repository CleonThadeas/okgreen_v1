<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->foreignId('user_id')
                  ->constrained('users','user_id')
                  ->onDelete('cascade');
            $table->enum('action_type', ['sell','buy','redeem','login','feedback']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_history');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('activity_history', function (Blueprint $table) {
            $table->id('activity_id');
            $table->foreignId('user_id')
                  ->constrained('users','user_id')
                  ->onDelete('cascade');
            $table->enum('activity_type', ['sell','buy','redeem','watch_edu','login','feedback']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_history');
    }
};

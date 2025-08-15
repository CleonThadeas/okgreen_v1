<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id('log_id');                        // log_id: bigint auto-increment
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('login_time');
            $table->string('device_info', 255)->nullable();
            $table->timestamps();                        // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_logs');
    }
};

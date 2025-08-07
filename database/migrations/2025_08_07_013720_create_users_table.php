<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name', 100);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable(); // ← tambahkan
            $table->string('password', 255);
            $table->rememberToken();                            // ← dan tambahkan
            $table->string('phone_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['user','petugas','admin'])->default('user');
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

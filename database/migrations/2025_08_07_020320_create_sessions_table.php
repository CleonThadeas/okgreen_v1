<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
        
            // ID user (tanpa FK langsung, supaya fleksibel)
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Guard/type untuk membedakan sumber user
            $table->string('guard')->nullable();
        
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};

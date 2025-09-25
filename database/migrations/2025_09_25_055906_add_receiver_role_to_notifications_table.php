<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('point_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->foreignId('user_id')
                  ->constrained('users', 'id') // FK ke users.id
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            // Ganti enum jadi string biar fleksibel
            $table->string('source', 50); 

            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('points_change');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_history');
    }
};

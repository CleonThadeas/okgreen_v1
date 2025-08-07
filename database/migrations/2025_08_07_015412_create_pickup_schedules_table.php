<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pickup_schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->foreignId('sell_id')
                  ->constrained('sell_waste', 'sell_id')
                  ->onDelete('cascade');
            $table->timestamp('scheduled_at');
            $table->enum('status', ['scheduled','completed','cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pickup_schedules');
    }
};

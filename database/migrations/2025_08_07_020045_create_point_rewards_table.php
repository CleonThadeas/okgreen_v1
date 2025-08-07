<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('point_rewards', function (Blueprint $table) {
            $table->id('reward_id');
            $table->string('reward_name', 100);
            $table->integer('required_points');
            $table->integer('stock');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('point_rewards');
    }
};

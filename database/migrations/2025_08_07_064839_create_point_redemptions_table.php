<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('point_redemptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('point_reward_id')->constrained('point_rewards')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('redeemed_at')->useCurrent();
        });
    }
    public function down(): void {
        Schema::dropIfExists('point_redemptions');
    }
};

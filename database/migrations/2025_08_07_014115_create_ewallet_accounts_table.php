<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ewallet_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('ewallet_accounts');
    }
};

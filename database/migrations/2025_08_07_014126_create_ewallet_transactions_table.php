<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ewallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('ewallet_account_id')->constrained('ewallet_accounts')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('ewallet_transactions');
    }
};

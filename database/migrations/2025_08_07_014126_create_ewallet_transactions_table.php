<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ewallet_transactions', function (Blueprint $table) {
            $table->id('ewallet_txn_id');
            $table->foreignId('ewallet_id')
                  ->constrained('ewallet_accounts', 'ewallet_id')
                  ->onDelete('cascade');
            $table->enum('type', ['credit','debit']);
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ewallet_transactions');
    }
};

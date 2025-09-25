<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('buy_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');

            $table->decimal('total_amount', 12, 2);
            // status: pending -> waiting for payment / staff verify; paid -> completed; canceling/cancelled handled below
            $table->enum('status', ['pending', 'paid', 'canceling', 'cancelled'])->default('pending');

            $table->text('qr_text')->nullable();              // simpan payload QR statis
            $table->timestamp('expired_at')->nullable();      // expirasi QR / batas waktu

            $table->unsignedBigInteger('handled_by_staff_id')->nullable()->index(); // id staff yang memverifikasi
            $table->timestamp('handled_at')->nullable();      // waktu staff verifikasi

            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('buy_transactions');
    }
};

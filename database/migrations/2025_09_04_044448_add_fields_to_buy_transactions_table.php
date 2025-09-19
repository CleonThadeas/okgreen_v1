<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('buy_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('buy_transactions', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('status');
            }
            if (!Schema::hasColumn('buy_transactions', 'shipping_method')) {
                $table->string('shipping_method', 50)->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('buy_transactions', 'receiver_name')) {
                $table->string('receiver_name', 150)->nullable()->after('shipping_method');
            }
            if (!Schema::hasColumn('buy_transactions', 'address')) {
                $table->string('address', 255)->nullable()->after('receiver_name');
            }
            if (!Schema::hasColumn('buy_transactions', 'phone')) {
                $table->string('phone', 30)->nullable()->after('address');
            }
            if (!Schema::hasColumn('buy_transactions', 'shipping_cost')) {
                $table->decimal('shipping_cost', 12, 2)->default(0)->after('phone');
            }
            if (!Schema::hasColumn('buy_transactions', 'qr_text')) {
                $table->text('qr_text')->nullable()->after('shipping_cost');
            }
            if (!Schema::hasColumn('buy_transactions', 'handled_by_staff_id')) {
                $table->unsignedBigInteger('handled_by_staff_id')->nullable()->after('qr_text');
            }
            if (!Schema::hasColumn('buy_transactions', 'handled_at')) {
                $table->timestamp('handled_at')->nullable()->after('handled_by_staff_id');
            }
        });
        
    }

    public function down(): void
    {
        Schema::table('buy_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'shipping_method',
                'receiver_name',
                'address',
                'phone',
                'shipping_cost',
                'expired_at',
                'qr_text',
                'handled_by_staff_id',
                'handled_at',
            ]);
        });
    }
};

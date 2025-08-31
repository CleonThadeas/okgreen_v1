<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterBuyTransactionsAddQrisAndExpiredAt extends Migration
{
    public function up()
    {
        // 1) Jika ada nilai 'cancelled', ubah ke 'canceling'
        DB::statement("UPDATE buy_transactions SET status = 'canceling' WHERE status = 'cancelled'");

        // 2) Ubah enum (MySQL) — menggunakan raw statement karena changetype enum tricky
        DB::statement("ALTER TABLE buy_transactions MODIFY status ENUM('pending','paid','canceling') NOT NULL DEFAULT 'pending'");

        Schema::table('buy_transactions', function (Blueprint $table) {
            $table->timestamp('expired_at')->nullable()->after('transaction_date');
            $table->unsignedBigInteger('handled_by_staff_id')->nullable()->after('status');
            $table->timestamp('handled_at')->nullable()->after('handled_by_staff_id');
            $table->text('qr_text')->nullable()->after('total_amount'); // simpan payload QRIS atau teks QR
            // jika mau file path para qrcode image: $table->string('qr_code_path')->nullable();
        });

        // optional: FK to staff (jika table staff ada)
        Schema::table('buy_transactions', function (Blueprint $table) {
            $table->foreign('handled_by_staff_id')->references('id')->on('staff')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('buy_transactions', function (Blueprint $table) {
            $table->dropForeign(['handled_by_staff_id']);
            $table->dropColumn(['expired_at','handled_by_staff_id','handled_at','qr_text']);
        });

        // revert enum back to include cancelled if necessary
        DB::statement("ALTER TABLE buy_transactions MODIFY status ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending'");
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared("
            CREATE TRIGGER trg_after_sell_insert
            AFTER INSERT ON sell_waste
            FOR EACH ROW
            BEGIN
                INSERT INTO user_activities (user_id, activity_type, reference_id, description, created_at, updated_at)
                VALUES (NEW.user_id, 'sell', NEW.id, CONCAT('Menjual sampah ID=', NEW.id), NOW(), NOW());
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_after_buy_transaction
            AFTER INSERT ON buy_transactions
            FOR EACH ROW
            BEGIN
                INSERT INTO user_activities (user_id, activity_type, reference_id, description, created_at, updated_at)
                VALUES (NEW.user_id, 'buy', NEW.id, CONCAT('Membeli sampah ID transaksi=', NEW.id), NOW(), NOW());
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_after_point_redemption
            AFTER INSERT ON point_redemptions
            FOR EACH ROW
            BEGIN
                INSERT INTO user_activities (user_id, activity_type, reference_id, description, created_at, updated_at)
                VALUES (NEW.user_id, 'redeem', NEW.id, CONCAT('Menukar poin reward ID=', NEW.point_reward_id), NOW(), NOW());
            END
        ");
    }

    public function down(): void {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_sell_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_buy_transaction");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_point_redemption");
    }
};

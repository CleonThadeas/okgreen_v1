<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Trigger setelah insert ke sell_waste -> masuk ke activity_history
        DB::unprepared("
            CREATE TRIGGER trg_after_sell_insert
            AFTER INSERT ON sell_waste
            FOR EACH ROW
            BEGIN
                INSERT INTO activity_history (
                    user_id,
                    activity_type,
                    reference_id,
                    description,
                    created_at
                )
                VALUES (
                    NEW.user_id,
                    'sell',
                    NEW.sell_id,
                    CONCAT('Menjual sampah ID=', NEW.sell_id),
                    CURRENT_TIMESTAMP
                );
            END
        ");

        // Trigger setelah insert ke point_redemptions -> masuk ke point_history
        DB::unprepared("
            CREATE TRIGGER trg_after_point_redemption
            AFTER INSERT ON point_redemptions
            FOR EACH ROW
            BEGIN
                INSERT INTO point_history (
                    user_id,
                    source,
                    reference_id,
                    points_change,
                    description,
                    created_at
                )
                VALUES (
                    NEW.user_id,
                    'penukaran',
                    NEW.redemption_id,
                    - (SELECT required_points FROM point_rewards WHERE reward_id = NEW.reward_id),
                    CONCAT('Tukar poin ke reward ID=', NEW.reward_id),
                    CURRENT_TIMESTAMP
                );
            END
        ");

        // 🆕 Trigger setelah insert ke buy_transactions -> masuk ke activity_history
        DB::unprepared("
            CREATE TRIGGER trg_after_buy_transaction
            AFTER INSERT ON buy_transactions
            FOR EACH ROW
            BEGIN
                INSERT INTO activity_history (
                    user_id,
                    activity_type,
                    reference_id,
                    description,
                    created_at
                )
                VALUES (
                    NEW.user_id,
                    'buy',
                    NEW.transaction_id,
                    CONCAT('Membeli sampah ID transaksi = ', NEW.transaction_id),
                    CURRENT_TIMESTAMP
                );
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_sell_insert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_point_redemption;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_buy_transaction;");
    }
};

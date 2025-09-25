<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('contact_replies')) {
            Schema::create('contact_replies', function (Blueprint $table) {
                $table->bigIncrements('reply_id');

                // reference to contact_messages.message_id (existing table may use message_id as PK)
                $table->unsignedBigInteger('message_id')->index();

                // sender id (could be user.id or staff.id)
                $table->unsignedBigInteger('sender_id')->index();

                // sender_role: 'user' or 'staff'
                $table->enum('sender_role', ['user', 'staff'])->default('staff')->index();

                $table->text('message');

                $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

                // Attempt to add FK to contact_messages.message_id if possible
                if (Schema::hasTable('contact_messages') && Schema::hasColumn('contact_messages', 'message_id')) {
                    try {
                        $table->foreign('message_id')->references('message_id')->on('contact_messages')->onDelete('cascade');
                    } catch (\Throwable $e) {
                        // skip FK if it fails (keeps migration robust)
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contact_replies')) {
            // attempt to drop FK first, best-effort
            Schema::table('contact_replies', function (Blueprint $table) {
                try {
                    $table->dropForeign(['message_id']);
                } catch (\Throwable $e) {
                    // ignore
                }
            });

            Schema::dropIfExists('contact_replies');
        }
    }
};

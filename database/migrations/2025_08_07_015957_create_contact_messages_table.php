<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // If the table exists, make safe alterations (add status/timestamps if missing)
        if (Schema::hasTable('contact_messages')) {
            Schema::table('contact_messages', function (Blueprint $table) {
                // Add 'status' enum if not present
                if (! Schema::hasColumn('contact_messages', 'status')) {
                    $table->enum('status', ['pending', 'replied', 'closed'])
                          ->default('pending')
                          ->after('message')
                          ->index();
                }

                // Ensure created_at/updated_at exist (legacy DB might not have them)
                if (! Schema::hasColumn('contact_messages', 'created_at')) {
                    $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->after('status');
                }
                if (! Schema::hasColumn('contact_messages', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->after('created_at');
                }
            });
            return;
        }

        // If table doesn't exist, create it in full
        Schema::create('contact_messages', function (Blueprint $table) {
            // follow your existing project's naming: use message_id as PK if consistent
            $table->bigIncrements('message_id');

            // reference to user who created the message
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->index();

            $table->string('subject', 255)->nullable(false);
            $table->text('message');

            $table->enum('status', ['pending', 'replied', 'closed'])->default('pending')->index();

            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void
    {
        // If we created the table in this migration (i.e. it exists and has message_id PK),
        // drop it. If the table pre-existed, we only remove columns we added.
        if (Schema::hasTable('contact_messages')) {
            // Determine whether to drop or revert safely:
            // If the table has 'message_id' as primary key and we created it, we could drop.
            // But to be safe, we will only drop 'status' column if it exists and do not drop the whole table.
            Schema::table('contact_messages', function (Blueprint $table) {
                if (Schema::hasColumn('contact_messages', 'status')) {
                    // Attempt to drop the status column
                    try {
                        $table->dropColumn('status');
                    } catch (\Throwable $e) {
                        // ignore if not droppable
                    }
                }
                // NOTE: do not drop timestamps to avoid data loss.
            });
        }
    }
};

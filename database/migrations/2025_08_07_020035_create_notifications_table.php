<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->bigIncrements('id');

                // receiver can be a user or staff; disambiguate with role
                $table->unsignedBigInteger('receiver_id')->index();
                $table->enum('receiver_role', ['user', 'staff'])->default('user')->index();

                $table->string('title', 255);
                $table->text('message');

                // 0 = unread, 1 = read
                $table->boolean('is_read')->default(false)->index();

                $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

                // NOTE: we intentionally do not add a foreign key on receiver_id because
                // receiver may be from users OR staff table (polymorphic-like).
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::dropIfExists('notifications');
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id('education_id');
            $table->string('title', 255);
            $table->text('content');
            $table->enum('type', ['article','video']);
            $table->string('source_url', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('educations');
    }
};

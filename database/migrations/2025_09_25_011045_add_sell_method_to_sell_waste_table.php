<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('sell_waste', function (Blueprint $table) {
        $table->enum('sell_method', ['drop_point','pickup'])
              ->default('drop_point')
              ->after('status');
    });
}

public function down()
{
    Schema::table('sell_waste', function (Blueprint $table) {
        $table->dropColumn('sell_method');
    });
}

};

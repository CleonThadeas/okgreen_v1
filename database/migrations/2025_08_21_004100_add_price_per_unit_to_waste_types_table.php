<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricePerUnitToWasteTypesTable extends Migration
{
    public function up()
    {
        Schema::table('waste_types', function (Blueprint $table) {
            if (!Schema::hasColumn('waste_types', 'price_per_unit')) {
                $table->decimal('price_per_unit', 12, 2)->default(0)->after('description');
            }
        });
    }

    public function down()
    {
        Schema::table('waste_types', function (Blueprint $table) {
            if (Schema::hasColumn('waste_types', 'price_per_unit')) {
                $table->dropColumn('price_per_unit');
            }
        });
    }
}

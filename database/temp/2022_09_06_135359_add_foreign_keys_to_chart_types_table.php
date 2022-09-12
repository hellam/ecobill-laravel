<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToChartTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chart_types', function (Blueprint $table) {
            $table->foreign(['class_id'], 'chart_class_id_fk')->references(['id'])->on('chart_class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chart_types', function (Blueprint $table) {
            $table->dropForeign('chart_class_id_fk');
        });
    }
}

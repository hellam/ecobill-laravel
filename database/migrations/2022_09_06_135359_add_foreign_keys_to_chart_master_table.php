<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToChartMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chart_master', function (Blueprint $table) {
            $table->foreign(['account_type'], 'chart_type_id_fk')->references(['id'])->on('chart_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chart_master', function (Blueprint $table) {
            $table->dropForeign('chart_type_id_fk');
        });
    }
}

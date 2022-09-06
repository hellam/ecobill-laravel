<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign(['chart_code'], 'account_chart_code_fk')->references(['account_code'])->on('chart_master');
            $table->foreign(['charge_chart_code'], 'charge_chart_code_fk')->references(['account_code'])->on('chart_master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign('account_chart_code_fk');
            $table->dropForeign('charge_chart_code_fk');
        });
    }
}

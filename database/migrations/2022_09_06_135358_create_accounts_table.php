<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('account_name', 250);
            $table->string('account_number', 250)->nullable();
            $table->string('entity_name', 250)->nullable();
            $table->string('entity_address', 250)->nullable();
            $table->string('curr_code', 15);
            $table->boolean('is_default')->nullable()->default(false);
            $table->timestamp('last_reconcile_date')->nullable();
            $table->timestamp('ending_reconcile_balance')->nullable();
            $table->bigInteger('chart_code')->index('account_chart_code_fk');
            $table->bigInteger('charge_chart_code')->index('charge_chart_code_fk');
            $table->bigInteger('created_by')->nullable()->default(0);
            $table->bigInteger('last_updated_by')->nullable()->default(0);
            $table->timestamps();
            $table->boolean('inactive')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}

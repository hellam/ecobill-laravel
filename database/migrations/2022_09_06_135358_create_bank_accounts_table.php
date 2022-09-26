<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('account_name', 250);
            $table->string('account_number', 250)->nullable();
            $table->string('entity_name', 250)->nullable();
            $table->string('entity_address', 250)->nullable();
            $table->string('currency', 15);
            $table->boolean('is_default')->nullable()->default(false);
            $table->timestamp('last_reconcile_date')->nullable();
            $table->timestamp('ending_reconcile_balance')->nullable();
            $table->bigInteger('chart_code');
            $table->bigInteger('charge_chart_code');
            $table->string("client_ref");
            $table->bigInteger("branch_id");
            $table->string("created_by", 100)->nullable();
            $table->string("updated_by", 100)->nullable();
            $table->string('supervised_by', 100)->nullable();
            $table->timestamp('supervised_at')->nullable();
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
        Schema::dropIfExists('bank_accounts');
    }
};

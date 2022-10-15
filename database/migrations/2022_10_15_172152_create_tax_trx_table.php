<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_trx', function (Blueprint $table) {
            $table->id();
            $table->string('trx_type', 45);
            $table->bigInteger('trx_no');
            $table->timestamp('trx_date');
            $table->bigInteger('tax_id');
            $table->double('rate');
            $table->tinyInteger('included_in_price')->default(1);
            $table->double('net_amount')->default(0);
            $table->double('amount')->default(0);
            $table->string('reference',100);
            $table->bigInteger('branch_id');
            $table->string('client_ref',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_trx');
    }
};

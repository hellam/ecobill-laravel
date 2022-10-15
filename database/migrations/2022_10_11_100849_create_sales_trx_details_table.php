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
        Schema::create('sales_trx_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trans_no');
            $table->string('trx_type',45);
            $table->string('barcode',100);
            $table->string('description', 255);
            $table->longText('long_description')->nullable();
            $table->double('qty_sent')->default(0);
            $table->double('unit_price')->default(0);
            $table->double('discount')->default(0);
            $table->double('invoiced')->default(0);
            $table->double('qty')->default(0);
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
        Schema::dropIfExists('sales_trx_details');
    }
};

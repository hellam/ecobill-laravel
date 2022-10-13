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
        Schema::create('bank_trx', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trans_no');
            $table->string('trx_type',45);
            $table->string('reference',100);
            $table->bigInteger('bank_id');
            $table->double('amount')->default(0);
            $table->timestamp('trx_date')->nullable();
            $table->timestamp('reconciled')->nullable();
            $table->bigInteger('branch_id');
            $table->string('client_ref',100)->nullable();
            $table->string("created_by",100)->nullable();
            $table->string("updated_by",100)->nullable();
            $table->string('supervised_by',100)->nullable();
            $table->timestamp('supervised_at')->nullable();
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
        Schema::dropIfExists('bank_trx');
    }
};

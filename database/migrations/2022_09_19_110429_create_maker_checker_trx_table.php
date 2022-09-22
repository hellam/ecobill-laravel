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
        Schema::create('maker_checker_trx', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('mc_type')->default(0)->comment('0-Single,1-Double');
            $table->string('trx_type',255)->nullable();
            $table->string('status',20)->default('pending');
            $table->longText('txt_data')->nullable();
            $table->string('method',10)->nullable();
            $table->string('description',255)->nullable();
            $table->string('url',255);
            $table->bigInteger('maker');
            $table->bigInteger('checker1')->nullable();
            $table->bigInteger('checker2')->nullable();
            $table->string('client_ref',255);
            $table->bigInteger('branch_id');
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
        Schema::dropIfExists('maker_checker_trx');
    }
};

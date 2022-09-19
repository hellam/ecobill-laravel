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
            $table->tinyInteger('trx_type');
            $table->string('status',20)->default('pending');
            $table->longText('txt_data')->nullable();
            $table->string('file_data',255);
            $table->string('url',255);
            $table->bigInteger('maker');
            $table->bigInteger('checker1');
            $table->bigInteger('checker2');
            $table->string('client_ref',255);
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

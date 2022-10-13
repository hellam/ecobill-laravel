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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('description',255);
            $table->bigInteger('trans_no');
            $table->timestamp('trx_date')->nullable();
            $table->string('trx_type',45);
            $table->string('file_name',100);
            $table->bigInteger('file_size');
            $table->string('file_type',60);
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
        Schema::dropIfExists('attachments');
    }
};

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
        Schema::create('sales_types', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->tinyInteger('tax_included')->default(1);
            $table->double('factor')->default(1);
            $table->tinyInteger('is_default')->default(0);
            $table->string('inactive')->default(0);
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
        Schema::dropIfExists('sales_types');
    }
};

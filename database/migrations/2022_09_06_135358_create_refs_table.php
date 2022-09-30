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
        Schema::create('refs', function (Blueprint $table) {
            $table->bigInteger('id')->default(0);
            $table->string('type')->default(0);
            $table->string('reference', 100);
            $table->string("client_ref",100)->nullable();

            $table->primary(['id', 'type']);
            $table->unique(['type', 'reference'], 'Type_Ref unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refs');
    }
};

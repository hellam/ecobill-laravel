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
        Schema::create('maker_checker_rules', function (Blueprint $table) {
            $table->id();
            $table->string('permission_code',255);
            $table->tinyInteger('maker_type')->default(0)->comment('0-Single,1-Double');
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
        Schema::dropIfExists('maker_checker_rules');
    }
};
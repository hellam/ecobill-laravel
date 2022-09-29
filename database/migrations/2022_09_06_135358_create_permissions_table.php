<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 250);
            $table->string('code', 250)->unique('code');
            $table->boolean('requires_maker_checker')->default(false);
            $table->boolean('requires_hq')->default(false);
            $table->string('maker_validator_function', 250)->nullable();
            $table->bigInteger('parent_id')->nullable();
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
        Schema::dropIfExists('permissions');
    }
}

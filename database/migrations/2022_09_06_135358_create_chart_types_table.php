<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_types', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 250)->nullable();
            $table->bigInteger('class_id')->index('chart_class_id_fk');
            $table->bigInteger('created_by')->nullable()->default(0);
            $table->bigInteger('last_updated_by')->nullable()->default(0);
            $table->timestamps();
            $table->boolean('inactive')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_types');
    }
}

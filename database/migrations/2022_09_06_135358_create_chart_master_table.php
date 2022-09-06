<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_master', function (Blueprint $table) {
            $table->bigInteger('account_code')->primary();
            $table->string('account_name', 250)->nullable();
            $table->bigInteger('account_type')->index('chart_type_id_fk');
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
        Schema::dropIfExists('chart_master');
    }
}

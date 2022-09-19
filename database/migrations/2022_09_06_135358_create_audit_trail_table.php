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
        Schema::create('audit_trail', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('type');
            $table->bigInteger('trans_no')->nullable();
            $table->bigInteger('user')->nullable()->default(0);
            $table->text('api_token')->nullable()->comment('API Key or Session ID');
            $table->text('description')->nullable();
            $table->text('model')->nullable();
            $table->string("client_ref");
            $table->text('request_details')->comment('Request details');
            $table->string('ip_address', 45)->comment('ip address');
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
        Schema::dropIfExists('audit_trail');
    }
};

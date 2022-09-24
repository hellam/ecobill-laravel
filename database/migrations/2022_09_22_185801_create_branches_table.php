<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->longText('address');
            $table->string('phone', 30);
            $table->string('email', 100);
            $table->string('bcc_email', 100);
            $table->string('tax_no', 100)->unique();
            $table->string('default_currency', 30);
            $table->string('logo', 255);
            $table->string('timezone', 100);
            $table->string('fiscal_year', 50);
            $table->string('tax_period', 50);
            $table->string('tax_last_period', 50);
            $table->string('client_ref', 255);
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
        Schema::dropIfExists('branches');
    }
};
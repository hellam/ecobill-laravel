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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('f_name',255);
            $table->string('l_name',255)->nullable();
            $table->string('short_name',255);
            $table->string('address',255)->nullable();
            $table->string('company',255)->nullable();
            $table->string('country',20);
            $table->string('image',255)->nullable();
            $table->integer('tax_id')->nullable();
            $table->string('currency',20);
            $table->integer('payment_terms')->nullable();
            $table->double('credit_limit')->nullable();
            $table->tinyInteger('credit_status')->nullable()->comment('0-eligible, 1-not');
            $table->string('sales_type')->nullable()->comment('wholesale/retail');
            $table->double('discount')->nullable()->comment('percentage');
            $table->string('notes')->nullable();
            $table->string('language')->default('en');
            $table->string('client_ref',100)->nullable();
            $table->string("created_by",100)->nullable();
            $table->string("updated_by",100)->nullable();
            $table->string('supervised_by',100)->nullable();
            $table->timestamp('supervised_at')->nullable();
            $table->string('inactive')->default(0);
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
        Schema::dropIfExists('customers');
    }
};

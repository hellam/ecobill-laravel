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
        Schema::create('customer_branch', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable()->comment('foreign_key');
            $table->string('f_name');
            $table->string('l_name')->nullable();
            $table->string('short_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('client_ref',100)->nullable();
            $table->string("created_by",100)->nullable();
            $table->string("updated_by",100)->nullable();
            $table->string('supervised_by',100)->nullable();
            $table->timestamp('supervised_at')->nullable();
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
        Schema::dropIfExists('customer_branch');
    }
};

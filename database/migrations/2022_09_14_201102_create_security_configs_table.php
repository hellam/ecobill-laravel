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
        Schema::create('security_configs', function (Blueprint $table) {
            $table->id();
            $table->string('client_ref');
            $table->longText('general_security')->default('[1,1]');
            $table->longText('password_policy')->default('[0,4,[1],1,1]');
            $table->string('created_by',100)->nullable();
            $table->string('updated_by',100)->nullable();
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
        Schema::dropIfExists('security_configs');
    }
};

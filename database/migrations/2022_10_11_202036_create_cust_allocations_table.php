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
        Schema::create('cust_allocations', function (Blueprint $table) {
            $table->id();
            $table->double('amount')->default(0);
            $table->date('date_alloc');
            $table->bigInteger('trans_no_from');
            $table->string('trans_type_from', 45);
            $table->bigInteger('trans_no_to');
            $table->string('trans_type_to', 45);
            $table->bigInteger('branch_id');
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
        Schema::dropIfExists('cust_allocations');
    }
};

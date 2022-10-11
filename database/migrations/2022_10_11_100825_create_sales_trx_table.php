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
        Schema::create('sales_trx', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trans_no');
            $table->string('trx_type',45);
            $table->bigInteger('customer_id');
            $table->bigInteger('customer_branch_id');
            $table->timestamp('trx_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('reference',100);
            $table->string('comments',255)->nullable();
            $table->string('delivery_address',255)->nullable();
            $table->string('contact_phone',20)->nullable();
            $table->string('contact_email',100)->nullable();
            $table->string('delivery_to',255)->nullable();
            $table->bigInteger('payment_terms');
            $table->double('amount')->default(0);
            $table->double('alloc')->default(0);
            $table->tinyInteger('is_tax_included')->default(0);
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
        Schema::dropIfExists('sales_trx');
    }
};

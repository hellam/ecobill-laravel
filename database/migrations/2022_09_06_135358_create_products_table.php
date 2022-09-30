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
        Schema::create('products', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('barcode', 250)->unique('product_barcode_index');
            $table->string('name', 250)->nullable();
            $table->longText('description')->nullable();
            $table->double('price')->nullable()->default(0);
            $table->double('cost')->nullable()->default(0);
            $table->integer('order')->nullable()->default(1);
            $table->bigInteger('category_id');
            $table->bigInteger('tax_id');
            $table->tinyInteger('type')->default(1)->comment('1-product or 2-subscription');
            $table->string("created_by",100)->nullable();
            $table->string("updated_by",100)->nullable();
            $table->string('supervised_by',100)->nullable();
            $table->timestamp('supervised_at')->nullable();
            $table->boolean('inactive')->nullable()->default(false);
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
        Schema::dropIfExists('products');
    }
};

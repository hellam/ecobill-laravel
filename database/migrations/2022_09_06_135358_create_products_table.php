<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->bigInteger('category_id')->index('product_category_id_fk');
            $table->bigInteger('tax_id')->index('product_tax_id_fk');
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
        Schema::dropIfExists('products');
    }
}

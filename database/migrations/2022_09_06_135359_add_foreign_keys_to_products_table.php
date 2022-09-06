<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['category_id'], 'product_category_id_fk')->references(['id'])->on('categories');
            $table->foreign(['tax_id'], 'product_tax_id_fk')->references(['id'])->on('tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('product_category_id_fk');
            $table->dropForeign('product_tax_id_fk');
        });
    }
}

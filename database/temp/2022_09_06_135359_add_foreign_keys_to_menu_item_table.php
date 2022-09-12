<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMenuItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_item', function (Blueprint $table) {
            $table->foreign(['group_id'], 'manu_item_group_id_fk')->references(['id'])->on('menu_item_group');
            $table->foreign(['menu_id'], 'parent_menu_id_fk')->references(['id'])->on('menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_item', function (Blueprint $table) {
            $table->dropForeign('manu_item_group_id_fk');
            $table->dropForeign('parent_menu_id_fk');
        });
    }
}

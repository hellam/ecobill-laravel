<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_item', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 250)->nullable();
            $table->text('url')->nullable();
            $table->text('description')->nullable();
            $table->string('icon', 250)->nullable();
            $table->boolean('is_shortcut')->nullable()->default(false);
            $table->integer('order')->nullable()->default(1);
            $table->bigInteger('menu_id')->index('parent_menu_id_fk');
            $table->bigInteger('group_id')->index('manu_item_group_id_fk');
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
        Schema::dropIfExists('menu_item');
    }
}

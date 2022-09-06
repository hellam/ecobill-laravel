<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('username', 20)->unique('username');
            $table->string('password', 250);
            $table->timestamp('password_expiry_date')->nullable();
            $table->timestamp('account_expiry_date')->nullable();
            $table->string('full_name', 100);
            $table->bigInteger('role_id')->index('access_level_fk');
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('image')->nullable();
            $table->boolean('failed_login_attempts')->nullable()->default(false);
            $table->boolean('account_locked')->nullable()->default(false);
            $table->string('language', 20)->default('en');
            $table->boolean('date_format')->default(false);
            $table->boolean('date_sep')->default(false);
            $table->boolean('tho_sep')->default(false);
            $table->boolean('dec_sep')->default(false);
            $table->boolean('prices_dec')->default(false);
            $table->boolean('qty_dec')->default(false);
            $table->boolean('rates_dec')->default(false);
            $table->string('theme', 20)->default('light');
            $table->timestamp('last_visit_date')->nullable();
            $table->string('startup_tab', 100)->default('/');
            $table->smallInteger('transaction_days')->default(30);
            $table->boolean('def_print_destination')->default(false);
            $table->bigInteger('created_by')->nullable()->default(0);
            $table->bigInteger('last_updated_by')->nullable()->default(0);
            $table->timestamps();
            $table->boolean('inactive')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

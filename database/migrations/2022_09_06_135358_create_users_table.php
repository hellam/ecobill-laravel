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
            $table->string('remember_token', 250);
            $table->string('uuid', 20);
            $table->timestamp('password_expiry_date')->nullable();
            $table->timestamp('account_expiry_date')->nullable();
            $table->string('full_name', 100);
            $table->bigInteger('role_id')->index('roles_fk');
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('image')->nullable();
            $table->tinyInteger('failed_login_attempts')->nullable()->default(0);
            $table->boolean('account_locked')->nullable()->default(false);
            $table->string('language', 20)->default('en');
            $table->string('date_format',45)->default('DDMMYYYY');
            $table->string('date_sep',1)->default('/');
            $table->string('tho_sep',1)->default(',');
            $table->string('dec_sep',1)->default('.');
            $table->string('prices_dec',1)->default('.');
            $table->string('qty_dec',1)->default('.');
            $table->string('rates_dec',1)->default('.');
            $table->string('theme', 20)->default('light');
            $table->string('startup_tab', 100)->default('/');
            $table->smallInteger('transaction_days')->default(30);
            $table->string('def_print_destination')->nullable();
            $table->string('two_factor',100)->nullable();
            $table->tinyInteger('first_time',1)->default(0);
            $table->string("created_by",100)->nullable();
            $table->string("updated_by",100)->nullable();
            $table->string('supervised_by',100)->nullable();
            $table->timestamp('supervised_at')->nullable();
            $table->boolean('inactive')->default(false);
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
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserOauthAccountTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_oauth_account', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('provider', 45);
            $table->string('account_id', 128);
            $table->text('token');
            $table->boolean('active');
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
        Schema::drop('user_oauth_account');
    }

}
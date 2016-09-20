<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token', 45)->unique();
            $table->string('name', 50)->nullable();
            $table->string('email', 190)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('registered_via', 16);
            $table->string('registered_via_id', 255)->nullable();
            $table->boolean('is_guest')->nullable();
            $table->integer('last_activity')->nullable();
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
        Schema::drop('user');
    }

}
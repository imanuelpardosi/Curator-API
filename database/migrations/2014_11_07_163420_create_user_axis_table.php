<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAxisTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_axis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('axis_type', 45);
            $table->integer('axis_id');
            $table->boolean('active');
            $table->integer('position');
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
        Schema::drop('user_axis');
    }

}
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDaemon extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daemon', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('command', 50);
            $table->text('arguments');
            $table->integer('interval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daemon');
    }

}

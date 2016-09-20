<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSourceTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 45);
            $table->text('url');
            $table->text('rss_url');
            $table->integer('rss_last_pubdate');
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
        Schema::drop('source');
    }

}
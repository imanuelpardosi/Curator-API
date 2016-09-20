<?php

use Illuminate\Database\Migrations\Migration;

class CreateCustomRssTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_rss', function ($table) {
            $table->increments('id');
            $table->integer('publisher_id');
            $table->string('url', 128)->nullable();
            $table->string('link_selector', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('custom_rss');
    }

}
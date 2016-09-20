<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSourceLayout extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_layout', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id');
            $table->text('title_xpath');
            $table->text('thumbnail_xpath');
            $table->text('content_xpath');
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
        Schema::drop('source_layout');
    }

}

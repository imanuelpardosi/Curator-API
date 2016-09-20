<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpengraphObjectTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opengraph_object', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('type', 45)->default('article');
            $table->text('image')->nullable();
            $table->text('url');
            $table->integer('article_id')->nullable();
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
        Schema::drop('opengraph_object');
    }

}

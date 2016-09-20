<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 190)->unique();
            $table->string('title', 255);
            $table->text('content');
            $table->text('json');
            $table->text('excerpt');
            $table->text('thumbnail')->nullable();
            $table->string('thumbnail_dimension', 10)->nullable();
            $table->dateTime('published_at');
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
        Schema::drop('article');
    }

}
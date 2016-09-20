<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableArticleScore extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_score', function (Blueprint $table) {
            $table->integer('article_id');
            $table->integer('twitter');
            $table->integer('facebook');
            $table->timestamps();

            $table->primary('article_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('article_score');
    }

}

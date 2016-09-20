<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocmedArticleSharer extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socmed_article_sharer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('url', 255);
            $table->string('type', 45);
            $table->integer('friend_id');
            $table->string('friend_name', 100);
            $table->string('friend_profile_image', 255);
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
        Schema::drop('socmed_article_sharer');
    }

}

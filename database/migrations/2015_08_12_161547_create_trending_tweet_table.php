<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrendingTweetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trending_tweet', function (Blueprint $table) {
            $table->integer('trending_keyword_id');
            $table->integer('tweet_id');
            $table->primary(['trending_keyword_id', 'tweet_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('trending_tweet');
    }
}

<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tweet_id', 200);
            $table->string('username', 100);
            $table->text('json');
            $table->smallInteger('type')->default(0);
            $table->integer('retweet_count')->default(0);
            $table->integer('favourites_count')->default(0);
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
        Schema::drop('tweet');
    }
}

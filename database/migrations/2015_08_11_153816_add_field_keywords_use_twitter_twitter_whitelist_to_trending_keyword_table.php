<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldKeywordsUseTwitterTwitterWhitelistToTrendingKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trending_keyword', function (Blueprint $table) {
            $table->text('keywords')->nullable()->after('title');
            $table->boolean('use_twitter')->after('keywords')->default(false);
            $table->text('twitter_whitelist')->after('use_twitter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trending_keyword', function (Blueprint $table) {
            $table->dropColumn('twitter_whitelist');
        });

        Schema::table('trending_keyword', function (Blueprint $table) {
            $table->dropColumn('use_twitter');
        });

        Schema::table('trending_keyword', function (Blueprint $table) {
            $table->dropColumn('keywords');
        });
    }
}

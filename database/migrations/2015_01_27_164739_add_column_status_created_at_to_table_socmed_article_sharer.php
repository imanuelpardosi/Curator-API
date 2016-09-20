<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStatusCreatedAtToTableSocmedArticleSharer extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('socmed_article_sharer', function (Blueprint $table) {
            $table->timestamp('status_created_at')->after('friend_profile_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('socmed_article_sharer', function (Blueprint $table) {
            $table->dropColumn('status_created_at');
        });
    }

}

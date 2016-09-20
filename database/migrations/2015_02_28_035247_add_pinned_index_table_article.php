<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPinnedIndexTableArticle extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->index(['deleted_at', 'pinned_until'], 'pinned_articles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            Schema::table('article', function (Blueprint $table) {
                $table->dropIndex('pinned_articles');
            });
        } catch (Exception $e) {
            // index may has been dropped by 2015_03_24_000210_rebuild_article_index migration
        }
    }

}

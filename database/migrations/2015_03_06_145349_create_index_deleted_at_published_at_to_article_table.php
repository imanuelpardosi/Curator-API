<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexDeletedAtPublishedAtToArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->index(['deleted_at', 'published_at'], 'check_new_stories');
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
                $table->dropIndex('check_new_stories');
            });
        } catch (Exception $e) {
            // do nothing, the index may has been dropped by 2015_03_24_000210_rebuild_article_index migration
        }
    }

}


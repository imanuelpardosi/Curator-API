<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RebuildArticleIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // Delete old index, wrap this on try-catch since we'll not gonna rebuild them on rollback
        try {
            Schema::table('article', function (Blueprint $table) {
                $table->dropIndex('pinned_articles');
                $table->dropIndex('check_new_stories');
                $table->dropIndex('pinned_articles_fixed');
            });

        } catch (PDOException $e) {

        }

        Schema::table('article', function (Blueprint $table) {
            $table->index(['pinned', 'curated_at'], 'pinned_articles');
            $table->index(['pinned', 'published_at'], 'non_pinned_articles');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('article', function (Blueprint $table) {
            $table->dropIndex('pinned_articles');
            $table->dropIndex('non_pinned_articles');
        });
	}

}

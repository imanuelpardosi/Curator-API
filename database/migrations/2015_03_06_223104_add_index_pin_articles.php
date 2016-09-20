<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIndexPinArticles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('article', function (Blueprint $table) {
            $table->boolean('pinned')->default(0)->after('deleted_at');

            $table->index(['deleted_at', 'pinned', 'curated_at'], 'pinned_articles_fixed');
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
				$table->dropIndex('pinned_articles_fixed');
			});
		} catch (Exception $e) {
			// Do nothing, may the index has been deleted by migration 2015_03_24_000210_rebuild_article_index
		}

		Schema::table('article', function (Blueprint $table) {
			$table->dropColumn('pinned');
		});
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixSourceTableDefaultValue extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('source', function(Blueprint $table)
		{
			$table->integer('rss_last_pubdate')->nullable()->change();
            $table->string('country', 2)->default('ID')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('source', function(Blueprint $table)
		{
			//
		});
	}

}

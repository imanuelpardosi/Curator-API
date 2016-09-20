<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixSourceLayoutTableDefaultValue extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('source_layout', function(Blueprint $table)
		{
            $table->string('name', 32)->nullable()->change();
            $table->text('sample_url')->nullable()->change();
            $table->text('title_xpath')->nullable()->change();
            $table->text('thumbnail_xpath')->nullable()->change();
            $table->text('content_xpath')->nullable()->change();
            $table->text('paging_xpath')->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('source_layout', function(Blueprint $table)
		{
			//
		});
	}

}

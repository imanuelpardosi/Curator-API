<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixTopicGroupMemberTableDefaultValue extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('topic_group_member', function(Blueprint $table)
		{
			$table->integer('featured')->default(0)->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('topic_group_member', function(Blueprint $table)
		{
			//
		});
	}

}

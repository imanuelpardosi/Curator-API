<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserProfilePicture extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\Schema::table('user', function(Blueprint $table)
		{
			$table->text('profile_picture')->nullable()->after('registered_via_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		\Schema::table('user', function(Blueprint $table)
		{
			$table->dropColumn('profile_picture');
		});
	}

}

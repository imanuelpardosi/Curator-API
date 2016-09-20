<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnImageToTrending extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('trending_keyword', function (Blueprint $table) {
            $table->string('image')->nullable()->after('position');
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
            $table->dropColumn('image');
        });
	}

}

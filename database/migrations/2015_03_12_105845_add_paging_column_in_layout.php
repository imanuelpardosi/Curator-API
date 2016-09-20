<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPagingColumnInLayout extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('source_layout', function (Blueprint $table) {
            $table->text('paging_xpath')->default('')->after('content_xpath');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('source_layout', function (Blueprint $table) {
            $table->dropColumn('paging_xpath');
        });
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ReindexDeviceTargetting extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('device', function (Blueprint $table) {
            $table->dropIndex('targetting');

            $table->index(['user_id', 'uuid', 'updated_at'], 'targetting');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('device', function (Blueprint $table) {
            $table->dropIndex('targetting');

            $table->index(['deleted_at', 'user_id', 'created_at'], 'targetting');
        });
	}

}

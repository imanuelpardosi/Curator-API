<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIndexTargetting extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('device', function (Blueprint $table) {
            $table->index(['deleted_at', 'user_id', 'created_at'], 'targetting');
        });

        try {
            Schema::table('user_axis', function (Blueprint $table) {

                    $table->index(['user_id', 'axis_type', 'axis_id'], 'targetting');
            });
        } catch (\Exception $e) {
        }
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
        });

        try {
            Schema::table('user_axis', function (Blueprint $table) {

                $table->dropIndex('targetting');
            });
        } catch (\Exception $e) {

        }
	}

}

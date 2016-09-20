<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldUsedToPromoRedemptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('promo_redemption', function (Blueprint $table) {
            $table->boolean('used')->after('user_id')->default(false);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('promo_redemption', function (Blueprint $table) {
            $table->dropColumn('used');
        });
	}

}

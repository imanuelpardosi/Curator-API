<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRangeOfDateToTrendingKeyword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trending_keyword', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable()->after('keywords');
            $table->dateTime('end_date')->nullable()->after('start_date');
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
            $table->dropColumn('end_date');
            $table->dropColumn('start_date');
        });
    }
}

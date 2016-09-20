<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCustomRss extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_rss', function (Blueprint $table) {
            $table->dropColumn('publisher_id');
        });

        Schema::table('custom_rss', function (Blueprint $table) {
            $table->text('xpath')->default('');
        });

        Schema::table('custom_rss', function (Blueprint $table) {
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_rss', function (Blueprint $table) {
            $table->dropColumn('xpath');
        });

        Schema::table('custom_rss', function (Blueprint $table) {
            $table->integer('publisher_id')->default(0);
        });

        Schema::table('custom_rss', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }

}

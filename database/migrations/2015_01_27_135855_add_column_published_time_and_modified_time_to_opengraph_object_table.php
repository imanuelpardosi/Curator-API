<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPublishedTimeAndModifiedTimeToOpengraphObjectTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opengraph_object', function (Blueprint $table) {
            $table->timestamp('published_time')->after('url')->nullable();
            $table->timestamp('modified_time')->after('url')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opengraph_object', function (Blueprint $table) {
            $table->dropColumn('published_time');
        });

        Schema::table('opengraph_object', function (Blueprint $table) {
            $table->dropColumn('modified_time');
        });
    }

}

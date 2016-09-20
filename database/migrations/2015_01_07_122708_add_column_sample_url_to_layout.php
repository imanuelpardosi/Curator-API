<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSampleUrlToLayout extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('source_layout', function (Blueprint $table) {

            $table->string('name', 32)->default('')->after('source_id');
            $table->text('sample_url')->default('')->after('name');
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
            $table->dropColumn('name');
        });

        Schema::table('source_layout', function (Blueprint $table) {
            $table->dropColumn('sample_url');
        });
    }

}

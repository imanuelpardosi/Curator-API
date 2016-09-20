<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToTopic extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topic', function (Blueprint $table) {
            $table->dropColumn('active');
        });

        Schema::table('topic', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topic', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('topic', function (Blueprint $table) {
            $table->boolean('active')->default(0);
        });
    }

}

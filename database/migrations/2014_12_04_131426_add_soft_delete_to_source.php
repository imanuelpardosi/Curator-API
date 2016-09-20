<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToSource extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('source', function (Blueprint $table) {
            $table->dropColumn('active');
        });

        Schema::table('source', function (Blueprint $table) {
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
        Schema::table('source', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('source', function (Blueprint $table) {
            $table->boolean('active')->default(0);
        });
    }

}

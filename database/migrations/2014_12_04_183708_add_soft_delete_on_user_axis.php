<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteOnUserAxis extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_axis', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_axis', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_axis', function (Blueprint $table) {
            $table->boolean('active')->default(0);
        });

        Schema::table('user_axis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIndexingOnUserAxis extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_axis', function (Blueprint $table) {
//            $table->softDeletes();
            $table->index(['deleted_at', 'user_id', 'position']);
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
            $table->dropIndex(['deleted_at', 'user_id', 'position']);
//            $table->dropSoftDeletes();
        });
    }

}

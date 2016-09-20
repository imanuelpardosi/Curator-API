<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidColumnOnDevices extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device', function (Blueprint $table) {
            $table->string('uuid', 128)->default('')->after('platform');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }

}

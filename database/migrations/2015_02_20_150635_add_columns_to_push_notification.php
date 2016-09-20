<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPushNotification extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_notification', function (Blueprint $table) {
            $table->text('filter')->nullable();
            $table->string('target')->default('');
            $table->string('image')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('push_notification', function (Blueprint $table) {
            $table->dropColumn('filter');
        });

        Schema::table('push_notification', function (Blueprint $table) {
            $table->dropColumn('target');
        });

        Schema::table('push_notification', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

}

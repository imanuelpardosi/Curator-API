<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsTypeModifyIdToPushNotification extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_notification', function (Blueprint $table) {
            $table->string('type', 50)->default('');
        });

        Schema::table('push_notification', function (Blueprint $table) {
            $table->renameColumn('article_id', 'object_id');
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
            $table->dropColumn('type');
        });

        Schema::table('push_notification', function (Blueprint $table) {
            $table->renameColumn('object_id', 'article_id');
        });
    }

}

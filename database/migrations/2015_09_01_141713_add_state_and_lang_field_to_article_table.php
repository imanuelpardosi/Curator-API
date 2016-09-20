<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateAndLangFieldToArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->tinyInteger('state')->nullable()->default(0);
            $table->string('lang', 3)->nullable()->default('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->dropColumn('state');
        });

        Schema::table('article', function (Blueprint $table) {
            $table->dropColumn('lang');
        });
    }
}

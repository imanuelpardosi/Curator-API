<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameFieldKeywordToTitleInTrendingKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trending_keyword', function ($table) {

            $table->renameColumn('keyword', 'title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trending_keyword', function ($table) {

            $table->renameColumn('title', 'keyword');
        });
    }
}

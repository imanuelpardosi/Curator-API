<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnCuratedAtToPinnedUntilOnArticleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article', function ($table) {
        
            $table->renameColumn('curated_expired', 'pinned_until');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article', function ($table) {
        
            $table->renameColumn('pinned_until', 'curated_expired');
        });
    }

}

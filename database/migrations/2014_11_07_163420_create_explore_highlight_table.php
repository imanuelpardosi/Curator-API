<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExploreHighlightTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('explore_highlight', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 45);
            $table->string('axis_type', 45);
            $table->integer('axis_id');
            $table->integer('position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('explore_highlight');
    }

}
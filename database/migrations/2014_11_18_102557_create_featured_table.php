<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('explore_highlight');

        Schema::create('featured', function (Blueprint $table) {
        
            $table->increments('id');
            $table->string('name', 45);
            $table->string('axis_type', 45);
            $table->integer('axis_id');
            $table->integer('position');
            $table->text('image');
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
        Schema::table('featured', function (Blueprint $table) {
        
            Schema::drop('featured');
        });
    }

}

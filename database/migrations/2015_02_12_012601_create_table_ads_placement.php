<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableAdsPlacement extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_placement', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ads_id');
            $table->string('placement_type');
            $table->string('placement_id');
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
        Schema::dropIfExists('ads_placement');
    }

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTopicGroupMemberTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_group_member', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 45);
            $table->integer('topic_group_id');
            $table->string('axis_type', 45);
            $table->integer('axis_id');
            $table->integer('position')->default("9999");
            $table->boolean('highlight');
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
        Schema::drop('topic_group_member');
    }

}
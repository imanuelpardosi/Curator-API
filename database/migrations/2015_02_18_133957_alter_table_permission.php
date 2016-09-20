<?php

use Illuminate\Database\Migrations\Migration;

class AlterTablePermission extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            \DB::statement('alter table permissions modify name varchar(200)');
        } catch (\Exception $e) {

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            \DB::statement('alter table permissions modify name varchar(16)');
        } catch (\Exception $e) {

        }
    }

}
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableForFieldCountryToTableSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('source', function (Blueprint $table) {
            $table->string('country', 2)->default(null)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('source', function (Blueprint $table) {
            $table->string('country', 2)->default('ID')->change();
        });
    }
}

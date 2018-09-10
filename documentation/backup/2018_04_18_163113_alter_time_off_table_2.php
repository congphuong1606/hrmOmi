<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimeOffTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('time_off', function ($table) {
            $table->dropColumn(['range','range_unit']);
            $table->datetime('end_datetime')->after('start_datetime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('time_off', function ($table) {
            $table->integer('range');
            $table->integer('range_unit');
            $table->dropColumn('end_datetime');
        });
    }
}

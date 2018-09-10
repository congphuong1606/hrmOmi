<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDayOffSubtractToTimeOnMonthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_on_month', function (Blueprint $table) {
            //
            $table->float('day_off_subtract_permit')->default(0);
            $table->float('day_off_subtract_ot')->default(0);
            $table->float('day_off_subtract_normal')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_on_month', function (Blueprint $table) {
            //
            $table->dropColumn(['day_off_subtract_permit', 'day_off_subtract_ot', 'day_off_subtract_normal']);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDayOffMultiPermitToTimeOnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_on', function (Blueprint $table) {
            //
            $table->integer('day_off_multi_permit')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_on', function (Blueprint $table) {
            //
            $table->dropColumn(['day_off_multi_permit']);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeOnAccumulatedYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_on_accumulated_year', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->float('day_off_permit_in_year')->default(0);
            $table->float('day_off_ot_in_year')->default(0);
            $table->float('day_off_accumulated_permit_previous_year')->default(0);
            $table->float('day_off_accumulated_ot_previous_year')->default(0);
            $table->float('day_off_remain_permit')->default(0);
            $table->float('day_off_remain_ot')->default(0);
            $table->float('day_off_accumulated_permit_before_reset')->default(0);
            $table->float('day_off_accumulated_ot_before_reset')->default(0);
            $table->date('reset_time')->nullable();
            $table->integer('year');
            $table->integer('from_month')->nullable();
            $table->integer('end_month')->nullable();
            $table->tinyInteger('is_working')->default(0);
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
        Schema::dropIfExists('time_on_accumulated_year');
    }
}

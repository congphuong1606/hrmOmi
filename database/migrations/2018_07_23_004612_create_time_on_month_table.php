<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeOnMonthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_on_month', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('month');
            $table->integer('year');
            $table->float('day_off')->default(0);
            $table->float('day_off_with_pay_permit')->default(0);
            $table->float('day_off_without_pay')->default(0);
            $table->float('day_off_with_pay_ot')->default(0);
            $table->float('day_off_remain_in_month_permit')->default(0);
            $table->float('day_off_remain_in_month_ot')->default(0);
            $table->float('day_off_accumulated_permit')->default(0);
            $table->float('day_off_accumulated_ot')->default(0);
            $table->float('day_off_subtract_salary')->default(0);
            $table->float('day_off_remain_permit')->default(0);
            $table->float('day_off_remain_ot')->default(0);
            $table->float('work_day')->default(0);
            $table->float('actual_work_day')->default(0);
            $table->integer('absent_permit')->default(0);
            $table->integer('absent_without_permit')->default(0);
            $table->integer('diligence')->default(0);
            $table->integer('employee_id')->unsigned();
            $table->tinyInteger('is_approved')->default(0);
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
        Schema::dropIfExists('time_on_month');
    }
}

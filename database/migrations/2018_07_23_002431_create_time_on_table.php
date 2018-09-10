<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeOnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_on', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('day_off')->nullable();
            $table->float('working_time')->nullable();
            $table->float('tc')->nullable();
            $table->float('hour')->nullable();
            $table->integer('day_off_half_permit')->default(0);
            $table->integer('day_off_full_permit')->default(0);
            $table->integer('day_off_permit')->default(0);
            $table->integer('work_online')->default(0);
            $table->integer('day_off_late_permit')->default(0);
            $table->integer('day_off_late_without_permit')->default(0);
            $table->integer('day_off_go_out')->default(0);
            $table->integer('day_off_leave_early_permit')->default(0);
            $table->integer('day_off_holiday')->default(0);
            $table->integer('day_off_late_ot')->default(0);
            $table->integer('late')->default(0);
            $table->integer('leave_early')->default(0);
            $table->integer('day_off_leave_early_without_permit')->default(0);
            $table->integer('day_off_without_permit')->default(0);
            $table->integer('day_off_ot')->default(0);
            $table->integer('time_on_month_id')->unsigned()->nullable();
            $table->tinyInteger('is_updated')->unsigned()->default(0);
            $table->tinyInteger('is_imported')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_on');
    }
}

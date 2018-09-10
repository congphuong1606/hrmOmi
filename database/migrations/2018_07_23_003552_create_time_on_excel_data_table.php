<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeOnExcelDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_on_excel_data', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->integer('employee_id')->unsigned()->nullable();
            $table->integer('time_on_excel_file_id')->unsigned();
            $table->integer('time_on_id')->unsigned()->nullable();
            $table->integer('day_off')->unsigned()->nullable();
            $table->float('working_time')->default(0);
            $table->tinyInteger('is_duplicate')->default(0);
            $table->tinyInteger('is_accepted')->default(0);
            $table->tinyInteger('is_imported')->default(0);
            $table->float('tc1')->default(0);
            $table->float('tc2')->default(0);
            $table->float('tc3')->default(0);
            $table->float('hour')->default(0);
            $table->integer('late')->default(0);
            $table->integer('leave_early')->default(0);
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
        Schema::dropIfExists('time_on_excel_data');
    }
}

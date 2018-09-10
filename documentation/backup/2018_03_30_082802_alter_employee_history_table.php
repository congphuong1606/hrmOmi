<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmployeeHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('employees_update_history', function ($table) {
            $table->dropUnique('employees_update_history_employee_code_unique');
            $table->dropUnique('employees_update_history_attendance_code_unique');
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
        Schema::table('employees_update_history', function ($table) {
            $table->integer('employee_code')->unique();
            $table->integer('attendance_code')->unique();
        });

    }
}

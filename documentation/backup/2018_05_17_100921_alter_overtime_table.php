<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterOvertimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('over_time',function ($table){
            $table->dropColumn(['start_datetime','end_datetime','employee_id','work_content']);
            $table->integer('project_category_id');
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
        Schema::table('over_time',function ($table){
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('employee_id');
            $table->string('work_content');
            $table->dropColumn('project_category_id');
        });
    }
}

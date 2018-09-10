<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeProjectManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_project_manager', function (Blueprint $table) {
            $table->integer('employee_id')->unsigned();
            $table->integer('project_manager_id')->unsigned();
            $table->timestamps();

            $table->primary(['employee_id', 'project_manager_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_project_manager');
    }
}

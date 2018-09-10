<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesUpdateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_update_history', function (Blueprint $table){
            $table->increments('id');
            $table->integer('employee_id');
            $table->char('full_name');
            $table->tinyInteger('department_id');
            $table->tinyInteger('position_id');
            $table->tinyInteger('job_status');
            $table->date('birth_day');
            $table->char('identification_number');
            $table->date('identification_date');
            $table->char('identification_place_of');
            $table->char('tax_code');
            $table->string('permanent_address');
            $table->string('temporary_address');
            $table->char('bank_number');
            $table->char('bank_name');
            $table->char('phone_number');
            $table->char('chatwork_account');
            $table->char('skype_account');
            $table->char('facebook_link');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('employees_update_history');
    }
}

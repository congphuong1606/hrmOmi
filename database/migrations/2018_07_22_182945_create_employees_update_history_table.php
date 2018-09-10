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
        Schema::create('employees_update_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->string('employee_code');
            $table->string('full_name');
            $table->string('email');
            $table->integer('department_id')->nullable()->unsigned();
            $table->integer('position_id')->nullable()->unsigned();
            $table->integer('job_status_id')->nullable()->unsigned();
            $table->integer('working_status_id')->nullable()->unsigned();
            $table->integer('late_reason_id')->nullable()->unsigned();
            $table->integer('direct_manager_id')->nullable()->unsigned();
            $table->date('birth_day')->nullable();
            $table->string('identification_number')->nullable();
            $table->date('identification_date')->nullable();
            $table->string('identification_place_of')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('temporary_address')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_user_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('chatwork_account')->nullable();
            $table->string('skype_account')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('avatar')->nullable();
            $table->string('japanese_certificate')->nullable();
            $table->date('update_date')->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('training_date')->nullable();
            $table->date('official_date')->nullable();
            $table->text('contact_user')->nullable();
            $table->string('distance')->nullable();
            $table->tinyInteger('gender')->default(0)->comment('0: Không xác định, 1: Nam, 2: Nữ');
            $table->tinyInteger('status');
            $table->tinyInteger('approved')->default(0)->comment('0: Chưa chấp nhận, 1: Chấp nhận');
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

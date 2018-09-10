<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeExcelDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_excel_data', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->nullable();
            $table->text('job_status')->nullable();
            $table->text('position')->nullable();
            $table->text('birthday')->nullable();
            $table->text('phone')->nullable();
            $table->text('personal_email')->nullable();
            $table->text('email')->nullable();
            $table->text('skype')->nullable();
            $table->text('facebook')->nullable();
            $table->text('checkin_date')->nullable();
            $table->text('training_start_date')->nullable();
            $table->text('employee_start_date')->nullable();
            $table->text('fingerprint_id')->nullable();
            $table->text('identification_number')->nullable();
            $table->text('identification_date')->nullable();
            $table->text('identification_place')->nullable();
            $table->text('tax_code')->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('temporary_address')->nullable();
            $table->text('bank_number')->nullable();
            $table->text('bank_user_name')->nullable();
            $table->text('bank_name')->nullable();
            $table->text('bank_branch')->nullable();
            $table->text('note')->nullable();
            $table->text('japanese_certificate')->nullable();
            $table->text('department')->nullable();
            $table->text('contact_user')->nullable();
            $table->text('distance')->nullable();
            $table->text('late_reason_detail')->nullable();
            $table->text('gender')->nullable();
            $table->integer('employee_id')->unsigned()->nullable();
            $table->integer('employee_excel_file_id')->unsigned();
            $table->tinyInteger('is_accepted')->default(0)->comment('0: Không chấp nhận, 1: Chấp nhận');
            $table->tinyInteger('is_duplicate')->default(0)->comment('0: Không trùng, 1: Trùng');
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
        Schema::dropIfExists('employee_excel_data');
    }
}

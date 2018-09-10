<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('employees', function ($table) {
            $table->increments('id')->change();
            $table->string('facebook_link')->nullable()->change();
            $table->string('skype_account')->nullable()->change();
            $table->string('birth_day')->nullable()->change();
            $table->string('identification_number')->nullable()->change();
            $table->string('identification_date')->nullable()->change();
            $table->string('identification_place_of')->nullable()->change();
            $table->string('tax_code')->nullable()->change();
            $table->string('permanent_address')->nullable()->change();
            $table->string('temporary_address')->nullable()->change();
            $table->string('bank_number')->nullable()->change();
            $table->string('phone_number')->nullable()->change();
            $table->string('chatwork_account')->nullable()->change();
            $table->string('bank_name')->nullable()->change();
            $table->string('personal_email')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('working_status_id');
            $table->boolean('deleted_flag')->nullable()->default(false);
            $table->integer('employee_code')->unique();
            $table->integer('attendance_code')->unique();
        });
        Schema::table('employees_update_history', function ($table) {
            $table->increments('id')->change();
            $table->string('facebook_link')->nullable()->change();
            $table->string('skype_account')->nullable()->change();
            $table->string('birth_day')->nullable()->change();
            $table->string('identification_number')->nullable()->change();
            $table->string('identification_date')->nullable()->change();
            $table->string('identification_place_of')->nullable()->change();
            $table->string('tax_code')->nullable()->change();
            $table->string('permanent_address')->nullable()->change();
            $table->string('temporary_address')->nullable()->change();
            $table->string('bank_number')->nullable()->change();
            $table->string('phone_number')->nullable()->change();
            $table->string('chatwork_account')->nullable()->change();
            $table->string('bank_name')->nullable()->change();
            $table->string('personal_email')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('deleted_flag')->nullable()->default(false);
            $table->integer('employee_code')->unique();
            $table->integer('attendance_code')->unique();
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
        Schema::table('employees', function ($table) {
            $table->dropColumn(['avatar', 'deleted_flag', 'working_status_id', 'employee_code', 'attendance_code']);
        });
        Schema::table('employees_update_history', function ($table) {
            $table->dropColumn(['avatar', 'deleted_flag']);
        });
    }
}

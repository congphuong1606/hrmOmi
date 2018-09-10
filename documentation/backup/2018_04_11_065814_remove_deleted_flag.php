<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RemoveDeletedFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('employees_job_status_history', function ($table) {
            $table->dropColumn(['deleted_flag']);
        });
        Schema::table('time_on', function ($table) {
            $table->dropColumn(['deleted_flag']);
        });
        Schema::table('time_off', function ($table) {
            $table->dropColumn(['deleted_flag']);
        });
        Schema::table('employees_update_history', function ($table) {
            $table->dropColumn(['deleted_flag']);
        });
        Schema::table('employees', function ($table) {
            $table->dropColumn(['deleted_flag']);
        });
        Schema::table('working_status', function ($table) {
            $table->softDeletes();
        });
        Schema::table('screen_categories', function ($table) {
            $table->softDeletes();
        });
        Schema::table('screens', function ($table) {
            $table->softDeletes();
        });
        Schema::table('employees_job_status_history', function ($table) {
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
        //
        Schema::table('employees_job_status_history', function ($table) {
            $table->boolean('deleted_flag')->nullable()->default(false);
        });
        Schema::table('time_on', function ($table) {
            $table->boolean('deleted_flag')->nullable()->default(false);
        });
        Schema::table('time_off', function ($table) {
            $table->boolean('deleted_flag')->nullable()->default(false);
        });
        Schema::table('employees_update_history', function ($table) {
            $table->boolean('deleted_flag')->nullable()->default(false);
        });
        Schema::table('employees', function ($table) {
            $table->boolean('deleted_flag')->nullable()->default(false);
        });
        Schema::table('working_status', function ($table) {
            $table->dropSoftDeletes();
        });
        Schema::table('screen_categories', function ($table) {
            $table->dropSoftDeletes();
        });
        Schema::table('screens', function ($table) {
            $table->dropSoftDeletes();
        });
        Schema::table('employees_job_status_history', function ($table) {
            $table->dropSoftDeletes();
        });
    }
}

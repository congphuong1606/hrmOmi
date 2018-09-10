<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedFlagAlterAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('time_on', function ($table) {
            $table->boolean('deleted_flag')->nullable()->default(false);
        });
        Schema::table('time_off', function ($table) {
            $table->boolean('deleted_flag')->nullable()->default(false);
        });
        Schema::table('employees_job_status_history', function ($table) {
            $table->boolean('deleted_flag')->nullable()->default(false);
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
        Schema::table('time_on', function ($table) {
            $table->dropColumn(['deleted_flag']);
        });
        Schema::table('time_off', function ($table) {
            $table->dropColumn(['deleted_flag']);
        });

        Schema::table('employees_job_status_history', function ($table) {
            $table->dropColumn(['deleted_flag']);
        });

    }
}

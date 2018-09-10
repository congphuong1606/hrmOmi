<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('projects', function ($table) {
            $table->increments('id');
            $table->integer('project_code')->unique();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('status');
            $table->integer('type');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('over_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->string('proposer');
            $table->string('approver');
            $table->string('work_content');
            $table->integer('approved')->default(0);
            $table->string('approved_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('over_time_details', function ($table) {
            $table->increments('id');
            $table->integer('over_time_id');
            $table->integer('user_id');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->string('content');
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
        //
        Schema::dropIfExists('over_times');
        Schema::dropIfExists('over_time_details');
        Schema::dropIfExists('projects');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('courses', function ($table) {
            $table->increments('id');
            $table->integer('course_category_id');
            $table->string('description');
            $table->string('current_order');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('sessions', function ($table) {
            $table->increments('id');
            $table->integer('course_id');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->string('trainer');
            $table->string('supporter')->nullable();
            $table->string('content')->nullable();
            $table->integer('room_category_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('session_user', function ($table) {
            $table->integer('session_id');
            $table->integer('user_id');
            $table->primary(['session_id','user_id']);
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
        Schema::dropIfExists('courses');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('session_user');
    }
}

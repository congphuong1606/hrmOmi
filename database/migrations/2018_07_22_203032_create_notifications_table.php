<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('body');
            $table->string('action')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('read')->default(0);
            $table->integer('time_off_id')->nullable()->unsigned();
            $table->integer('over_time_id')->nullable()->unsigned();
            $table->integer('course_id')->nullable()->unsigned();
            $table->integer('training_id')->nullable()->unsigned();
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
        Schema::dropIfExists('notifications');
    }
}

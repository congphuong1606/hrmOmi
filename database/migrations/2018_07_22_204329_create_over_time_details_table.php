<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverTimeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('over_time_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('over_time_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
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
        Schema::dropIfExists('over_time_details');
    }
}

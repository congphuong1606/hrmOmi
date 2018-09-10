<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeOnTimeOffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_on_time_off', function (Blueprint $table) {
            $table->integer('time_on_id')->unsigner();
            $table->integer('time_off_id')->unsigner();
            $table->float('time')->unsigner()->default(0);
            $table->timestamps();

            $table->primary(['time_on_id', 'time_off_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_on_time_off');
    }
}

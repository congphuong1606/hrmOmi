<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeOnAdditionDayOffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_on_addition_day_off', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('time_on_accumulated_year_id')->unsigned();
            $table->float('time')->default(0);
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('manual_type')->default(1);
            $table->integer('year')->default(1);
            $table->integer('month')->default(1);
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('time_on_addition_day_off');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeOffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_off', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->tinyInteger('status'); //Di muon, ve som, giua gio, xin nghi
            $table->tinyInteger('approved'); //phê duyệt
            $table->string('approved_reason')->nullable(); //Lý do phê duyệt
            $table->string('reason')->nullable(); //Lý do
            $table->string('detailed_reason');
            $table->string('backup_person')->nullable();
            $table->integer('file_id')->nullable()->unsigned();
            $table->tinyInteger('flow_type')->default(0);
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
        Schema::dropIfExists('time_off');
    }
}

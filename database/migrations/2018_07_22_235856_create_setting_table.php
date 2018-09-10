<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_morning')->default('08:00:00');
            $table->time('end_morning')->default('11:30:00');
            $table->time('start_afternoon')->default('13:00:00');
            $table->time('end_afternoon')->default('17:30:00');
            $table->integer('in_late_threshold')->default(15);
            $table->integer('time_off_registration_threshold')->default(3);
            $table->string('hr_and_administration_mail')->default('hr_admin@ominext.com');
            $table->string('bom_mail')->default('bom_omi@ominext.com');
            $table->integer('notification_frequency')->default(600);
            $table->string('time_off_reset_milestone')->default('30-06');
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
        Schema::dropIfExists('setting');
    }
}

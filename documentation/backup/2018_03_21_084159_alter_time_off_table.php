<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimeOffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('time_off', function ($table) {
            $table->boolean('approved')->default(false);
            $table->string('detailed_reason');
            $table->string('backup_person');
            $table->string('project_manger');
            $table->string('team_leader');
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
        Schema::table('time_off',function ($table){
           $table->dropColumn(['detailed_reason','backup_person','project_manger','team_leader']);
        });
    }
}

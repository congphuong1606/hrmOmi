<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimeOffTable3 extends Migration
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
            $table->string('backup_person')->nullable()->change();
            $table->string('project_manger')->nullable()->change();
            $table->string('team_leader')->nullable()->change();
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
        Schema::table('time_off', function ($table) {
            $table->string('backup_person');
            $table->string('project_manger');
            $table->string('team_leader');
        });
    }
}

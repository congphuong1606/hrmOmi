<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSavedNameToTimeOnExcelFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_on_excel_file', function (Blueprint $table) {
            //
            $table->string('saved_name')->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_on_excel_file', function (Blueprint $table) {
            //
            $table->dropColumn(['saved_name']);
        });
    }
}

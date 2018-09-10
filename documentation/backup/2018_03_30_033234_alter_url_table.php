<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUrlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('urls', function ($table) {
            $table->integer('url')->change();
            $table->renameColumn('url', 'url_id');
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
        Schema::table('urls', function ($table) {
            $table->string('url')->change();
            $table->renameColumn('url_id', 'url');
        });
    }
}

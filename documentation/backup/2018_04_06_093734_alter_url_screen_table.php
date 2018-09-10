<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUrlScreenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('urls');
        Schema::table('screens', function ($table) {
            $table->string('url');
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
        Schema::create('urls', function ($table) {
            $table->increments('id');
            $table->string('url');
            $table->integer('screen_id');
            $table->timestamps();
        });
        Schema::table('screens', function ($table) {
            $table->dropColumn(['url_id']);
        });
    }
}

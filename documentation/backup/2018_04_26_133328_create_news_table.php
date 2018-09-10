<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('news', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->string('link')->nullable();
            $table->string('description')->nullable();
            $table->integer('scope')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('news_user', function ($table) {
            $table->integer('news_id');
            $table->integer('user_id');
            $table->primary(['user_id', 'news_id']);
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
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_user');
    }
}

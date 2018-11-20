<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MovieTheatreTmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_theatre_tmp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movieId');
            $table->integer('theatreId');
            $table->dateTime('dateTime');	  
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
        Schema::drop('movie_theatre_tmp');
    }
}

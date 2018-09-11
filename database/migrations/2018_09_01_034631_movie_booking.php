<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MovieBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_booking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movieId');
            $table->integer('theatreId');
            $table->string('bookingLink',500)->nullable();	              
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movie_booking');
    }
}

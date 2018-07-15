<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Url extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url', function (Blueprint $table) {
            $table->increments('id');
            $table->string('urlName');
            $table->integer('groceryId')->default(0);
            $table->integer('restaurantId')->default(0);
            $table->integer('a')->default(0);
            $table->integer('b')->default(0);
            $table->integer('c')->default(0);
            $table->integer('d')->default(0);
            $table->integer('e')->default(0);
            $table->integer('f')->default(0);
            $table->integer('g')->default(0);
            $table->integer('h')->default(0);
            $table->integer('i')->default(0);
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
        Schema::dropIfExists('url');
    }
}

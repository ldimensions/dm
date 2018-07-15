<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Photo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo', function (Blueprint $table) {
            $table->increments('photoId');
            $table->string('photoName');
            $table->integer('is_primary')->default(0);
            $table->integer('order')->default(0);
            $table->boolean('is_deleted',1)->default(0);
            $table->boolean('is_disabled',1)->default(0);            
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
        Schema::dropIfExists('photo');
    }
}

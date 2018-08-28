<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Events extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('website',100)->nullable();             
            $table->string('categoryId')->nullable();
            $table->string('organizerName')->nullable();
            $table->string('organizerContact')->nullable();
            $table->string('email')->nullable();;
            $table->string('phone')->nullable();;
            $table->string('url');            
            $table->integer('addressId');
            $table->integer('siteId');
            $table->integer('order')->default(0);
            $table->integer('premium')->default(0);
            $table->boolean('is_disabled',1)->default(0);
            $table->boolean('is_deleted',1)->default(0);
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
        Schema::drop('events');
    }
}
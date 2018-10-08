<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RestaurantTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_tmp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('referenceId')->default(0);;
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('urlId');
            $table->integer('ethnicId');
            $table->integer('addressId');
            $table->integer('siteId');
            $table->integer('status')->comment('1 = Save, 2 = Submitted', '3 = Appproved', '4 = Rejected');
            $table->string('website',100)->nullable();            
            $table->text('workingTime')->nullable();
            $table->integer('order')->default(0);
            $table->integer('premium')->default(0);
            $table->boolean('is_deleted',1)->default(0);
            $table->boolean('is_disabled',1)->default(0);
            $table->string('statusMsg',500)->nullable();            
            $table->integer('updated_by')->unassigned();
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
        Schema::drop('restaurant_tmp');
    }
}

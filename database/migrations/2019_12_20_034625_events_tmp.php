<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventsTmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_tmp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('referenceId')->default(0);
            $table->text('description')->nullable();
            $table->string('website',100)->nullable();             
            $table->string('categoryId')->nullable();
            $table->string('organizerName')->nullable();
            $table->string('organizerContact')->nullable();
            $table->string('email')->nullable();;
            $table->string('phone')->nullable();;
            $table->string('urlId');            
            $table->integer('addressId');
            $table->integer('siteId');
            $table->integer('order')->default(0);
            $table->integer('premium')->default(0);
            $table->boolean('is_disabled',1)->default(0);
            $table->boolean('is_deleted',1)->default(0);
            $table->integer('status')->comment('1 = Save, 2 = Submitted', '3 = Appproved', '4 = Rejected');
            $table->text('statusMsg')->nullable();
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
        Schema::drop('events_tmp');
    }
}

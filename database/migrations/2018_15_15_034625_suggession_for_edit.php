<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SuggessionForEdit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggession_for_edit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->nullable();;
            $table->string('phone')->nullable();;
            $table->text('suggession')->nullable();
            $table->string('url');
            $table->integer('type');     
            $table->boolean('is_read',1)->default(1);            
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
        Schema::drop('suggession_for_edit');
    }
}

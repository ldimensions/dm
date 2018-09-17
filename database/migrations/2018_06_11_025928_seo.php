<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Seo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->increments('seoId');
            $table->integer('urlId')->nullable();
            $table->string('keyValue')->nullable();
            $table->integer('index')->nullable();            
            $table->string('SEOMetaTitle')->nullable();
            $table->string('SEOMetaDesc')->nullable();
            $table->string('SEOMetaPublishedTime')->nullable();
            $table->string('SEOMetaKeywords')->nullable();
            $table->string('OpenGraphTitle')->nullable();
            $table->string('OpenGraphDesc')->nullable();
            $table->string('OpenGraphUrl')->nullable();
            $table->string('OpenGraphPropertyType')->nullable();
            $table->string('OpenGraphPropertyLocale')->nullable();
            $table->string('OpenGraphPropertyLocaleAlternate')->nullable();
            $table->string('OpenGraph')->nullable();
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
        Schema::dropIfExists('seo');
    }
}

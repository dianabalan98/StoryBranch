<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_stories', function (Blueprint $table) {
            $table->bigIncrements('id');

            // FK cu stories table
            $table->bigInteger('story_id')->unsigned();
            $table->foreign('story_id')
                  ->references('id')->on('stories')
                  ->onDelete('cascade');

            // FK cu users table
            $table->bigInteger('reader_id')->unsigned();
            $table->foreign('reader_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
                  
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
        Schema::dropIfExists('favorite_stories');
    }
}

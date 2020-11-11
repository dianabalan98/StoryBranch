<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');

            // FK cu stories table
            $table->bigInteger('story_id')->unsigned();
            $table->foreign('story_id')
                  ->references('id')->on('stories')
                  ->onDelete('cascade');

            // FK cu nodes table
            $table->bigInteger('node_id')->unsigned();
            $table->foreign('node_id')
                  ->references('id')->on('nodes')
                  ->onDelete('cascade');

            // FK cu users table
            $table->bigInteger('reader_id')->unsigned();
            $table->foreign('reader_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->text('body');  //continut comentariu

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
        Schema::dropIfExists('comments');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_authors', function (Blueprint $table) {
            $table->bigIncrements('id');

            // FK cu users table
            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')
                  ->references('id')->on('users')
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
        Schema::dropIfExists('favorite_authors');
    }
}

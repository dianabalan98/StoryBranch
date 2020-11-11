<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->string('title');
            $table->text('description');
            $table->string('cover')->default('defaultCover.png');

            // FK pt tabelele Category si Level: sunt obligatorii
            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('cascade');

            $table->boolean('published')->default(false);
            $table->boolean('completed')->default(false);
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
        Schema::dropIfExists('stories');
    }
}

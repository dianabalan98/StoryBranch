<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->bigIncrements('id');

            // FK cu stories table
            $table->bigInteger('story_id')->unsigned();
            $table->foreign('story_id')
                  ->references('id')->on('stories')
                  ->onDelete('cascade');

            $table->string('subtitle');
            $table->longtext('body')->default("Your new story node.");
            $table->boolean('root')->default(false);
            $table->boolean('display_subtitle')->default(false);

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
        Schema::dropIfExists('nodes');
    }
}

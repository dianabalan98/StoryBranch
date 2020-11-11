<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_relations', function (Blueprint $table) {
            $table->bigIncrements('id');

            // FK cu parent node table
            $table->bigInteger('parent_id')->unsigned();
            $table->foreign('parent_id')
                  ->references('id')->on('nodes')
                  ->onDelete('cascade');

            $table->string('choice');

            // FK cu child node table
            $table->bigInteger('child_id')->unsigned();
            $table->foreign('child_id')
                  ->references('id')->on('nodes')
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
        Schema::dropIfExists('node_relations');
    }
}

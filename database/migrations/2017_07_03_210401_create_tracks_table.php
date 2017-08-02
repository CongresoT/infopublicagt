<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('round_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->foreign('round_id')
                ->references('id')->on('rounds');
            $table->foreign('subject_id')
                ->references('id')->on('subjects');
            $table->float('score')->nullable();
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
        Schema::dropIfExists('tracks');
    }
}

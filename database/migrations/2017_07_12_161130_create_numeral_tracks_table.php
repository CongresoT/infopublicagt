<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumeralTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numeral_tracks', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('round_id')->unsigned();
            $table->integer('numeral_id')->unsigned();
            $table->foreign('round_id')
                ->references('id')->on('rounds');
            $table->foreign('numeral_id')
                ->references('id')->on('numerals');
            $table->float('score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('numeral_tracks');
    }
}

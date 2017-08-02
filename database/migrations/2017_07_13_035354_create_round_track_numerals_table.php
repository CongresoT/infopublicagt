<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundTrackNumeralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('round_track_numerals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('track_id')->unsigned();
            $table->integer('numeral_id')->unsigned();
            $table->foreign('track_id')
                ->references('id')->on('tracks');
            $table->foreign('numeral_id')
                ->references('id')->on('numerals');
            $table->float('score')->nullable();
			$table->string('links',750)->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('round_track_numerals');
    }
}

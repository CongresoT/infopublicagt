<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumeralsSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numerals_subjects', function (Blueprint $table) {
            $table->integer('numeral_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->foreign('numeral_id')
                ->references('id')->on('numerals');
            $table->foreign('subject_id')
                ->references('id')->on('subjects');
            $table->primary(['numeral_id','subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('numerals_subjects');
    }
}

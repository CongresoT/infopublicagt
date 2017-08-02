<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question',350);
            $table->enum('q_type',['p','c'])->comment('parent or child');
            $table->boolean('parent_response')->nullable();
            $table->integer('numeral_id')->unsigned();
            $table->integer('indicator_id')->unsigned()->nullable();
            $table->foreign('numeral_id')
                ->references('id')->on('numerals');
            $table->foreign('indicator_id')
                ->references('id')->on('indicators');
            $table->timestamps();
        });
    }

    public function numeral(){
        return $this->belongsTo('numeral');
    }

    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicators');
    }
}

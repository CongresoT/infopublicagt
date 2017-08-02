<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumeralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numerals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('script');
            $table->integer('article_id')->unsigned();
            $table->foreign('article_id')
                ->references('id')->on('articles');
        });
    }



    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('numerals');
    }
}

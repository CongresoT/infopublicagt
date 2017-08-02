<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',250);
            $table->string('url',250)->nullable();
            $table->string('uaip_person',250)->nullable();;
            $table->string('phone',100)->nullable();;
            $table->string('email',100)->nullable();
            $table->boolean('enabled');
            $table->integer('sector_id')->unsigned();
            $table->foreign('sector_id')
                ->references('id')->on('sectors');
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
        Schema::dropIfExists('subjects');
    }
}

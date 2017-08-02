<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
			$table->increments('id');
			
            $table->integer('track_id')->unsigned();

            $table->integer('indicator_id')->unsigned();

            $table->foreign('track_id')
                ->references('id')->on('tracks');

            $table->foreign('indicator_id')
                ->references('id')->on('indicators');

            $table->enum('answer',['Y','N','NA'])->nullable()->comment('yes, no, does not apply, null if it has not being answered.');
            $table->enum('q_type',['p','c'])->comment('parent or child');
            $table->text('specialist_comments')->nullable();
            $table->string('links',750)->nullable();

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
        Schema::dropIfExists('questions');
    }
}

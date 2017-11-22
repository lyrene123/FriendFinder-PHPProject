<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class');
            $table->string('section');
            $table->integer('day')->unsigned();
            $table->integer('start')->unsigned();
            $table->integer('end')->unsigned();
            $table->integer('teacher_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->foreign("teacher_id")->references("id")->on("teachers");
            $table->foreign("course_id")->references("id")->on("courses");
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
        Schema::dropIfExists('course_schedules');
    }
}

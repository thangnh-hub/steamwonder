<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbLessonSylabusQuizsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_lesson_sylabus_quizs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lesson_id')->comment('Bài học');
            $table->bigInteger('parent_id')->nullable();
            $table->string('type')->comment('TV,NP,Nghe,Nói,Đọc,Viết');
            $table->string('form')->nullable()->default('default')->comment('Default, Nghe, Nói');
            $table->string('style')->nullable();
            $table->string('question')->nullable();
            $table->json('json_params')->nullable();
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
        Schema::dropIfExists('tb_lesson_sylabus_quizs');
    }
}

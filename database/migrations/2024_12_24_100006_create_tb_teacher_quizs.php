<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTeacherQuizs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_teacher_quizs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('question');
            $table->json('json_params')->nullable();
            $table->string('status')->nullable();
            $table->integer('admin_created_id')->nullable();
            $table->integer('admin_updated_id')->nullable();
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
        Schema::dropIfExists('tb_teacher_quizs');
    }
}

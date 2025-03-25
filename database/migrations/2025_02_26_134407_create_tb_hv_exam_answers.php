<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbHvExamAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_hv_exam_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('id_question');
            $table->string('answer')->nullable()->comment('Đáp án');
            $table->integer('correct_answer')->default(0)->comment('Đáp án đúng');
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
        Schema::dropIfExists('tb_hv_exam_answers');
    }
}

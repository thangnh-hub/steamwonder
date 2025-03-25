<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbLessonSylabusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_lesson_sylabus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syllabus_id')->constrained('tb_syllabus')->comment('ID chương trình học');
            $table->string('ordinal')->nullable();
            $table->text('title')->nullable()->comment('Tiêu đề');
            $table->string('score_name')->nullable()->comment('Tên điểm buổi học');
            $table->float('score_value')->nullable()->comment('điểm buổi học');
            $table->text('content')->nullable()->comment('Nội dung buổi học');
            $table->text('target')->nullable()->comment('Mục tiêu buổi học');
            $table->text('teacher_mission')->nullable()->comment('Nhiệm vụ giảng viên');
            $table->text('student_mission')->nullable()->comment('Nhiệm vụ học viên');
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
        Schema::dropIfExists('tb_lesson_sylabus');
    }
}

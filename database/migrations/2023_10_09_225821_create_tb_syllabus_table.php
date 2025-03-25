<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSyllabusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_syllabus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained('tb_subjects')->comment('ID Trình độ');
            $table->string('name')->comment('Tên chương trình');
            $table->integer('min_score')->nullable()->comment('có thể chỉ dùng để tạo dữ liệu');
            $table->integer('group_score')->nullable()->comment('Số nhóm điểm');
            $table->integer('lesson')->nullable()->comment('số buổi học');
            $table->integer('lesson_min')->nullable()->comment('số buổi học tối thiểu');
            $table->boolean('is_approve')->default(false)->comment('Duyệt chương trình');
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
        Schema::dropIfExists('tb_syllabus');
    }
}

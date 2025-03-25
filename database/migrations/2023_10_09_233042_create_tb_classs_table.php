<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbClasssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_classs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained('tb_subjects')->comment('ID Trình độ');
            $table->foreignId('syllabus_id')->constrained('tb_syllabus')->comment('ID Chương trình');
            $table->foreignId('course_id')->constrained('tb_courses')->comment('ID Khóa học');
            $table->foreignId('period_id')->constrained('tb_periods')->comment('ID Ca học');
            $table->integer('area_id');
            $table->integer('room_id');
            $table->string('name')->comment('Tên chương trình');
            $table->string('start_date')->nullable()->comment('Ngày bắt đầu');
            $table->dateTime('end_date')->nullable()->comment('Ngày kết thúc');
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
        Schema::dropIfExists('tb_classs');
    }
}

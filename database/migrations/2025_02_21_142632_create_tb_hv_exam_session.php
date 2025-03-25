<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbHvExamSession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_hv_exam_session', function (Blueprint $table) {
            $table->id('id');
            $table->integer('time_exam')->comment('Phút');
            $table->date('day_exam')->comment('Ngày thi');
            $table->time('start_time')->nullable()->comment('Giờ bắt đầu');
            $table->time('end_time')->nullable()->comment('Giờ kết thúc');
            $table->integer('id_invigilator')->nullable()->comment('Giám thị');
            $table->integer('id_grader_exam')->nullable()->comment('Người chấm');
            $table->integer('id_level')->comment('Trình độ');;
            $table->string('organization')->comment('Goethe/telc');
            $table->string('skill_test')->comment('Nghe, nói, đọc, viết');
            $table->string('status')->nullable();
            $table->json('json_params')->nullable();
            $table->foreignId('admin_created_id')->nullable()->constrained('admins');
            $table->foreignId('admin_updated_id')->nullable()->constrained('admins');
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
        Schema::dropIfExists('tb_hv_exam_session');
    }
}

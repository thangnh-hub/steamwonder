<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbHvExamSessionUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_hv_exam_session_user', function (Blueprint $table) {
            $table->id();
            $table->integer('id_exam_session');
            $table->integer('id_user');
            $table->integer('id_class');
            $table->integer('id_grader_exam')->nullable()->comment('Người chấm');
            $table->integer('id_level')->comment('Trình độ');;
            $table->string('skill_test')->comment('Nghe, nói, đọc, viết');
            $table->string('status')->nullable();
            $table->integer('score')->nullable()->comment('Điểm');
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
        Schema::dropIfExists('tb_hv_exam_session_user');
    }
}

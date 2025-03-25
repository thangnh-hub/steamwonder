<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbHvExamTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_hv_exam_topics', function (Blueprint $table) {
            $table->id();
            $table->integer('id_level')->comment('Trình độ');
            $table->string('is_type')->comment('1, 2, 3, 4, 5');
            $table->string('skill_test')->comment('Nghe, nói, đọc, viết');
            $table->string('content')->comment('Nội dung phần thi');
            $table->string('audio')->comment('File Audio ')->nullable();
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
        Schema::dropIfExists('tb_hv_exam_topics');
    }
}

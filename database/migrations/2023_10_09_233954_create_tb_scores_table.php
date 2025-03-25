-<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('tb_classs')->comment('ID Lớp học trình độ');
            $table->foreignId('user_id')->constrained('users')->comment('ID Học viên');
            $table->foreignId('score_id')->constrained('tb_group_scores_syllabus')->comment('ID Nhóm đầu điểm');
            $table->float('min_score')->nullable()->comment('Điểm tối thiểu');
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
        Schema::dropIfExists('tb_scores');
    }
}

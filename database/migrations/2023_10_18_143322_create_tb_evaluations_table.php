<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('admins')->comment('ID admin (student type)');
            $table->foreignId('teacher_id')->constrained('admins')->comment('ID admin (teacher type)');
            $table->string('evaluation')->comment('Nội dung đánh giá');
            $table->string('is_type')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('tb_evaluations');
    }
}

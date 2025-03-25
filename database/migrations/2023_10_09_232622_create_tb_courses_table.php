<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained('tb_levels')->comment('ID Trình độ');
            $table->foreignId('syllabus_id')->constrained('tb_syllabus')->comment('ID Chương trình');
            $table->string('name')->comment('Tên chương trình');
            $table->string('status')->default('active')->comment('Trạng thái');
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
        Schema::dropIfExists('tb_courses');
    }
}

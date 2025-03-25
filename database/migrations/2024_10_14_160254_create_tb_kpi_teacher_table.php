<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbKpiTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_kpi_teacher', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id')->nullable()->comment('Giáo viên');
            $table->string('kpi_year')->nullable()->comment('Năm');
            $table->string('kpi_class')->nullable()->comment('Kpi quản lý lớp');
            $table->string('kpi_behavior')->nullable()->comment('Tác phong');
            $table->string('kpi_total')->nullable()->comment('tổng kpi');
            $table->string('time_report')->nullable()->comment('Năm');
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
        Schema::dropIfExists('tb_kpi_teacher');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbClassAttendanceStudentMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_class_attendance_student_meal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('tb_students');
            $table->foreignId('class_id')->constrained('tb_class');
            $table->date('meal_day')->comment('Ngày áp dụng');
            $table->string('status')->default('active')->comment('active/deactive');
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
        Schema::dropIfExists('tb_class_attendance_student_meal');
    }
}

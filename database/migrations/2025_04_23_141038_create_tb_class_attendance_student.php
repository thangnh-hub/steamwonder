<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbClassAttendanceStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_class_attendance_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_attendance_id')->constrained('tb_class_attendance');
            $table->foreignId('student_id')->constrained('tb_students');
            $table->dateTime('checkin_at')->nullable();
            $table->foreignId('checkin_parent_id')->nullable()->constrained('tb_parents');
            $table->foreignId('checkin_teacher_id')->nullable()->constrained('admins');
            $table->dateTime('checkout_at')->nullable();
            $table->foreignId('checkout_parent_id')->nullable()->constrained('tb_parents');
            $table->foreignId('checkout_teacher_id')->nullable()->constrained('admins');
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
        Schema::dropIfExists('tb_class_attendance_student');
    }
}

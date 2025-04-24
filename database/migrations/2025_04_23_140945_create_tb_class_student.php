<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbClassStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('tb_class');
            $table->foreignId('student_id')->constrained('tb_students');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('stop_at')->nullable();
            $table->string('type')->nullable();
            $table->json('json_params')->nullable();
            $table->string('status')->nullable()->default('active');
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
        Schema::dropIfExists('tb_class_student');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbClassTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_class_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('tb_class');
            $table->foreignId('teacher_id')->constrained('admins');
            $table->dateTime('start_at');
            $table->dateTime('stop_at')->nullable();
            $table->boolean('is_teacher_main')->default(false);
            $table->json('json_params')->nullable();
            $table->string('status')->nullable()->default('active');
            $table->integer('iorder')->default(0);
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
        Schema::dropIfExists('tb_class_teacher');
    }
}

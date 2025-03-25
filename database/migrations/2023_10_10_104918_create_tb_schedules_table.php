<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable()->comment('Ngày học');
            $table->foreignId('period_id')->constrained('tb_periods')->comment('ID Ca học');
            $table->foreignId('room_id')->constrained('tb_rooms')->comment('ID phòng học');
            $table->foreignId('class_id')->constrained('tb_classs')->comment('ID phòng học');
            $table->foreignId('area_id')->constrained('tb_areas')->comment('ID phòng học');
            $table->integer('teacher_id');
            $table->string('status')->nullable();
            $table->text('file')->nullable();
            $table->integer('is_add_more')->nullable();
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
        Schema::dropIfExists('tb_schedules');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_attendances', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable()->comment('Ngày học');
            $table->foreignId('schedule_id')->constrained('tb_schedules')->comment('ID Lịch học');
            $table->foreignId('user_id')->constrained('users')->comment('Học viên ID');
            $table->string('status')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('tb_attendances');
    }
}

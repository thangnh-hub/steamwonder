<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên phòng');
            $table->foreignId('area_id')->constrained('tb_areas')->comment('ID Khu vực');
            $table->integer('slot')->nullable()->comment('Sức chứa');
            $table->string('type')->nullable()->comment('Loại phòng');
            $table->boolean('is_internal')->default(false)->comment('Phòng nội bộ ?');
            $table->dateTime('start_date')->nullable()->comment('Ngày bắt đầu hoạt động');
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
        Schema::dropIfExists('tb_rooms');
    }
}

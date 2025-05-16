<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbServiceConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_service_config', function (Blueprint $table) {
            $table->id();
            $table->string('type', 255)->comment('Kiểu phí áp dụng: Trông muộn(late_fee) - Trông thứ 7(saturday_fee) - Dịch vụ bán trú(boarding_fee)');
            $table->dateTime('time_start')->nullable()->comment('Ngày bắt đầu khả dụng');
            $table->dateTime('time_end')->nullable()->comment('Ngày kết thúc khả dụng');
            $table->time('block_start')->nullable()->comment('Thời gian tính phí từ');
            $table->time('block_end')->nullable()->comment('thời gian tính phí đến');
            $table->double('price', 10, 2)->default(0)->comment('Số tiền phải đóng');
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
        Schema::dropIfExists('tb_service_config');
    }
}

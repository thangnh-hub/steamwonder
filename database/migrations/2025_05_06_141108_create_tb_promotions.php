<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPromotions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained('tb_areas');
            $table->string('promotion_code', 100);
            $table->string('promotion_name', 255);
            $table->text('description')->nullable();
            $table->string('promotion_type')->default('percent')->comment('Loại khuyến mãi: percent / fixed_amout / add_month');
            $table->dateTime('time_start')->comment('Ngày bắt đầu khả dụng');
            $table->dateTime('time_end')->comment('Ngày kết thúc khả dụng');
            $table->json('json_params')->nullable()->comment('Lưu thông tin các chính sách %, giảm trừ... mà dịch vụ được hưởng tại thời điểm khả dụng');
            $table->string('status')->default('active')->comment('Trạng thái: active / deactive');
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
        Schema::dropIfExists('tb_promotions');
    }
}

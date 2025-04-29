<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDeductions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained('tb_areas');
            $table->string('code', 50)->comment('Mã giảm trừ');
            $table->string('name', 255)->comment('Tên giảm trừ');
            $table->text('description')->nullable();
            $table->boolean('is_cumulative')->default(false)->comment('Cho phép giảm lỹ kế hay không');
            $table->string('type')->default('percent')->comment('Kiểu giảm trừ: Giảm theo % (percent), giảm theo số tiền (fixed_amount), Thu số tiền cố định (extra_amount)');
            $table->string('condition_type')->default('start_day_range')->comment('Kiểu điều kiện: Theo ngày nghỉ (absent_days), Theo ngày đi học (present_days), Theo ngày nhập học (start_day_range),...');
            $table->json('json_params')->nullable()->comment('Lưu thông tin cấu hình, condition (điều kiện để được hưởng) kèm các mức giảm trừ theo mỗi dịch vụ kiểu như: {  "start": 1,  "end": 10,  "services": [    {      "service_id": 101,      "value": 10,},    {      "service_id": 102,      "value": 200000}  ]}');
            $table->string('status')->default('active')->comment('Trạng thái: active, deactive');
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
        Schema::dropIfExists('tb_deductions');
    }
}

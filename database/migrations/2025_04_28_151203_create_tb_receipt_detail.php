<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbReceiptDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_receipt_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained('tb_receipt');
            $table->foreignId('service_id')->constrained('tb_service');
            $table->foreignId('student_id')->constrained('tb_students');
            $table->date('month')->comment('Tháng áp dụng');
            $table->double('by_number', 10, 2)->default(0)->comment('Số lượng dự kiến (thu trước theo dịch vụ)');
            $table->double('spent_number', 10, 2)->default(0)->comment('Số lượng sử dụng thực tế (đối soát theo dịch vụ)');
            $table->decimal('unit_price', 10, 2)->default(0)->comment('Đơn giá dịch vụ');
            $table->decimal('amount', 10, 2)->default(0)->comment('Số tiền dịch vụ trong tháng');
            $table->decimal('discount_amount', 10, 2)->default(0)->comment('Tiền giảm trừ trong tháng');
            $table->decimal('adjustment_amount', 10, 2)->default(0)->comment('Truy thu (+) / Hoàn trả (-) thực tế sau đối soát');
            $table->decimal('final_amount', 10, 2)->default(0)->comment('Số tiền cuối cùng phải thu sau giảm trừ & điều chỉnh');
            $table->string('status')->default('expected')->comment('Trạng thái xử lý: expected / adjusted / finalized');
            $table->text('note')->nullable();
            $table->json('json_params')->nullable()->comment('Lưu thông tin cấu hình của dịch vụ mà student được hưởng tương ứng với dịch vụ này');
            $table->foreignId('admin_created_id')->nullable()->constrained('admins');
            $table->foreignId('admin_updated_id')->nullable()->constrained('admins');
            $table->timestamps();

            // Index để tăng hiệu năng
            $table->index(['month']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_receipt_detail');
    }
}

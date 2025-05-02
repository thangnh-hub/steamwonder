<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbReceipt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_receipt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained('tb_areas');
            $table->foreignId('student_id')->constrained('tb_students');
            $table->foreignId('payment_cycle_id')->constrained('tb_payment_cycle');
            $table->foreignId('prev_receipt_id')->nullable()->constrained('tb_receipt')->comment('Biên lai trước đó (nếu có) để đối soát');
            $table->decimal('prev_balance', 10, 2)->default(0)->comment('Số dư mang từ kỳ trước (dương: dư, âm: nợ)');
            $table->string('receipt_code', 100);
            $table->string('receipt_name', 255);
            $table->decimal('total_amount', 10, 2)->default(0)->comment('Tổng số tiền cần thu');
            $table->decimal('total_discount', 10, 2)->default(0)->comment('Tổng giảm trừ');
            $table->decimal('total_adjustment', 10, 2)->default(0)->comment('Tổng cộng các truy thu (+) / hoàn trả (-) (từ receipt_details)');
            $table->decimal('total_final', 10, 2)->default(0)->comment('Tổng tiền thực tế sau đối soát tất cả dịch vụ');
            $table->decimal('total_paid', 10, 2)->default(0)->comment('Đã thu');
            $table->decimal('total_due', 10, 2)->default(0)->comment('Số tiền còn phải thu (+) hoặc thừa (-)');
            $table->dateTime('period_start')->comment('Ngày bắt đầu kỳ thu');
            $table->dateTime('period_end')->comment('Ngày kết thúc kỳ thu');
            $table->dateTime('receipt_date')->nullable()->comment('Ngày lập biên lai');
            $table->string('status')->default('pending')->comment('Trạng thái xử lý: pending / paid / completed');
            $table->text('note')->nullable();
            $table->foreignId('cashier_id')->nullable()->constrained('admins')->comment('Người thu tiền');
            $table->json('json_params')->nullable()->comment('Lưu thông tin các chính sách, giảm trừ... mà student được hưởng tại thời điểm tạo phiếu');
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
        Schema::dropIfExists('tb_receipt');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbReceiptAdjustmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_receipt_adjustment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->nullable()->constrained('tb_receipt');
            $table->foreignId('service_id')->nullable()->constrained('tb_service');
            $table->foreignId('student_id')->constrained('tb_students');
            $table->string('type')->comment('dunokytruoc,khuyenmai,phatsinh,doisoat');
            $table->foreignId('receipt_id_old')->nullable()->constrained('tb_receipt');
            $table->date('month')->nullable()->comment('Tháng áp dụng');
            $table->decimal('amount', 12, 2)->default(0)->comment('Số tiền dịch vụ trong tháng');
            $table->decimal('discount_amount', 12, 2)->default(0)->comment('Tiền giảm trừ trong tháng');
            $table->decimal('final_amount', 12, 2)->default(0)->comment('Số tiền cuối cùng phải thu sau giảm trừ & điều chỉnh');
            $table->text('note')->nullable();
            $table->string('status')->default('expected')->comment('Trạng thái xử lý: expected / adjusted / finalized');
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
        Schema::dropIfExists('tb_receipt_adjustment');
    }
}

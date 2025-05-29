<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbReceiptTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_receipt_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained('tb_receipt')->comment('Mã TBP');
            $table->decimal('paid_amount', 20, 2)->default(0)->comment('Số tiền đã thu');
            $table->foreignId('cashier')->constrained('admins')->comment('Thu ngân');
            $table->date('payment_date')->nullable()->comment('Ngày thanh toán');
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
        Schema::dropIfExists('tb_receipt_transaction');
    }
}

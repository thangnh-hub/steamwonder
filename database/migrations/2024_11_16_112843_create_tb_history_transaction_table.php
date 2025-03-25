<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbHistoryTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_history_transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id');
            $table->string('type_revenue')->comment('Khoản thu');
            $table->double('amount_paid', 20, 2)->nullable()->comment('Số tiền đã trả');
            $table->date('time_payment')->nullable()->comment('Thời gian thanh toán');
            $table->json('json_params')->nullable();
            $table->unsignedBigInteger('admin_created_id')->nullable()->comment('Người tạo');
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
        Schema::dropIfExists('tb_history_transaction');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPaymentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_payment_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('admins');
            $table->string('dep_id')->nullable();
            $table->text('content')->nullable();
            $table->string('qr_number')->nullable();
            $table->string('advance_money')->nullable();
            $table->json('json_params')->nullable();
            $table->string('status')->default('new');
            $table->integer('total_money_vnd')->default(0);
            $table->integer('total_money_euro')->default(0);
            $table->integer('total_money_advance')->default(0)->comment('Tổng tiền đã ứng');
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
        Schema::dropIfExists('tb_payment_request');
    }
}

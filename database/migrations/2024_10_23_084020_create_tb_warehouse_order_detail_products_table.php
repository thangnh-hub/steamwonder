<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbWarehouseOrderDetailProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_warehouse_order_detail_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable()->comment('kỳ');
            $table->integer('product_id')->nullable();
            $table->string('quantity')->nullable()->comment('Số lượng');
            $table->string('subtotal_money')->nullable()->comment('Tổng tiền');
            $table->string('department')->nullable()->comment('Bộ phận phòng ban');
            $table->string('status')->nullable()->comment('Trạng thái (Duyệt hay chưa duyệt)');
            $table->json('json_params')->nullable();
            $table->integer('admin_created_id')->nullable();
            $table->integer('admin_updated_id')->nullable();
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
        Schema::dropIfExists('tb_warehouse_order_detail_products');
    }
}

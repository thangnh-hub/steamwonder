<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbWarehouseOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_warehouse_order_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Tên đề xuất');
            $table->integer('area_id')->nullable()->comment('Cơ sở/ Khu vực');
            $table->string('period')->nullable()->comment('kỳ');
            $table->string('total_money')->nullable()->comment('Tổng tiền');
            $table->string('status')->nullable()->comment('Trạng thái (Duyệt hay chưa duyệt)');
            $table->json('json_params')->nullable();
            $table->integer('admin_created_id')->nullable();
            $table->integer('admin_updated_id')->nullable();
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('tb_warehouse_order_products');
    }
}

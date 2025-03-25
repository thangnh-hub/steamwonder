<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbWarehouseProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_warehouse_product', function (Blueprint $table) {
            $table->id();
            $table->integer('warehouse_category_id')->nullable()->comment('Danh mục');
            $table->string('warehouse_type')->nullable()->comment('Loại');
            $table->string('code')->nullable()->comment('Mã sp');
            $table->string('name')->nullable()->comment('Tên');
            $table->string('unit')->nullable()->comment('Đơn vị tính');
            $table->string('price')->nullable()->comment('Đơn giá');
            $table->string('status')->nullable()->comment('trạng thái');
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
        Schema::dropIfExists('tb_warehouse_product');
    }
}

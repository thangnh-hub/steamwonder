<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMealWarehouseEntryDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_meal_warehouse_entry_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('tb_areas')->comment('Cơ sở');
            $table->foreignId('entry_id')->constrained('tb_meal_warehouse_entry')->comment('phiếu');
            $table->foreignId('ingredient_id')->constrained('tb_meal_ingredients')->comment('Thực phẩm');
            $table->foreignId('unit_id')->nullable()->comment('Đơn vị tinhs');
            $table->decimal('quantity', 10, 2)->default(0)->comment('Số lượng');
            $table->decimal('price', 10, 2)->default(0)->comment('Giá nhập');
            $table->string('type')->nullable()->comment('nhập_xuất');
            $table->json('json_params')->nullable();
            $table->string('status')->nullable()->default('active');
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
        Schema::dropIfExists('tb_meal_warehouse_entry_detail');
    }
}

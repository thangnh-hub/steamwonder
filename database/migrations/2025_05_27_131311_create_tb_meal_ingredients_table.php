<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMealIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_meal_ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Tên nhà cung cấp');
            $table->string('description')->nullable()->comment('Mô tả');
            $table->foreignId('default_unit_id')->nullable()->constrained('tb_meal_units')->comment('Đơn vị tính mặc định');
            $table->foreignId('ingredient_category_id')->nullable()->constrained('tb_meal_ingredients_category')->comment('Danh mục thực phẩm');
            $table->string('type')->nullable()->comment('Lưu kho hay tươi');
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
        Schema::dropIfExists('tb_meal_ingredients');
    }
}

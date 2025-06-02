<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMealMenuIngredientsDailyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_meal_menu_ingredients_daily', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_daily_id')->constrained('tb_meal_menu_daily')->comment('Thực đơn ngày');
            $table->foreignId('ingredient_id')->constrained('tb_meal_ingredients')->comment('Thực phẩm');
            $table->string('value')->nullable();
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
        Schema::dropIfExists('tb_meal_menu_ingredients_daily');
    }
}

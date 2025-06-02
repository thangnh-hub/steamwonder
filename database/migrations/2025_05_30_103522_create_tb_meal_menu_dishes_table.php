<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMealMenuDishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_meal_menu_dishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('tb_meal_menu_planning')->comment('Thực đơn');
            $table->foreignId('dishes_id')->constrained('tb_meal_dishes')->comment('Món ăn');
            $table->json('json_params')->nullable();
            $table->string('type')->nullable()->comment('Bữa sáng, trưa, tối');
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
        Schema::dropIfExists('tb_meal_menu_dishes');
    }
}

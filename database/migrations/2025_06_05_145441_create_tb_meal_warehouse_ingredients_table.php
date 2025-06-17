<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMealWarehouseIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_meal_warehouse_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('tb_areas')->comment('Cơ sở');
            $table->foreignId('ingredient_id')->constrained('tb_meal_ingredients')->comment('Thực phẩm');
            $table->foreignId('entry_id')->constrained('tb_meal_warehouse_entry')->comment('phiếu');
            $table->float('quantity')->nullable();
            $table->json('json_params')->nullable();
            $table->string('status')->nullable()->default('new');
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
        Schema::dropIfExists('tb_meal_warehouse_ingredients');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMealDishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_meal_dishes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Tên nhà cung cấp');
            $table->string('description')->nullable()->comment('Mô tả');
            $table->string('dishes_type')->nullable()->comment('Canh,Mặn,Xào luộc');
            $table->string('dishes_time')->nullable()->comment('Sáng trưa chiều');
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
        Schema::dropIfExists('tb_meal_dishes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMealMenuDailyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_meal_menu_daily', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_menu_planning_id')->nullable()->constrained('tb_meal_menu_planning')->comment('Thực đơn mẫu');
            $table->foreignId('meal_age_id')->nullable()->constrained('tb_meal_ages')->comment('Nhóm tuổi');
            $table->date('date')->nullable()->comment('Ngày áp dụng thực đơn');
            $table->string('name')->nullable()->comment('Tên thực đơn');
            $table->string('code')->nullable()->comment('Mã thực đơn');
            $table->string('season')->nullable()->comment('Theo mùa');
            $table->text('description')->nullable()->comment('Mô tả');
            $table->integer('count_student')->nullable()->comment('Số trẻ');
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
        Schema::dropIfExists('tb_meal_menu_daily');
    }
}

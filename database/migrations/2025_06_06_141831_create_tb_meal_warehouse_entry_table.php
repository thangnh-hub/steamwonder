<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMealWarehouseEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_meal_warehouse_entry', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('tb_areas')->comment('Cơ sở');
            $table->string('name')->comment('Tên phiếu');
            $table->string('code')->nullable()->comment('Mã phiếu');
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
        Schema::dropIfExists('tb_meal_warehouse_entry');
    }
}

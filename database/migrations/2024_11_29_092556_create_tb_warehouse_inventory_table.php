<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbWarehouseInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_warehouse_inventory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('person_id')->comment('Người kiểm kê');
            $table->bigInteger('area_id')->nullable();
            $table->bigInteger('warehouse_id')->nullable()->comment('Kho');
            $table->integer('department')->nullable()->comment('Phòng ban');
            $table->integer('positions_id')->nullable()->comment('Vị trí');
            $table->string('status')->nullable();
            $table->string('period')->nullable()->comment('Kỳ kiểm kê');
            $table->date('date_received')->nullable()->comment('Ngày kiểm kê');
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
        Schema::dropIfExists('tb_warehouse_inventory');
    }
}

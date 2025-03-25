<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbWarehousePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_warehouse_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('warehouse_id')->nullable()->comment('Kho');
            $table->string('status')->nullable()->comment('Trạng thái');
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
        Schema::dropIfExists('tb_warehouse_positions');
    }
}

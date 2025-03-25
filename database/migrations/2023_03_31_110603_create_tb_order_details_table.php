<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('tb_orders')->onDelete('cascade');
            $table->integer('item_id');
            $table->string('name');
            $table->integer('quantity')->nullable();
            $table->double('price', 20, 2)->nullable();
            $table->double('discount', 20, 2)->nullable();
            $table->json('json_params')->nullable(); // Store extension information: SKU, tax,...
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
        Schema::dropIfExists('tb_order_details');
    }
}

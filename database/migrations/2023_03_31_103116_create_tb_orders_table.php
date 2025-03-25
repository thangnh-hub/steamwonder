<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_orders', function (Blueprint $table) {
            $table->id();
            $table->string('is_type')->nullable()->default('product');
            $table->integer('customer_id')->nullable();
            $table->json('json_params')->nullable(); // Store all order's information (email, name, phone, note,...)
            $table->double('subtotal', 20, 2)->nullable();
            $table->double('shipping', 20, 2)->nullable();
            $table->double('discount', 20, 2)->nullable();
            $table->double('tax', 20, 2)->nullable();
            $table->double('other_fee', 20, 2)->nullable();
            $table->double('total', 20, 2)->nullable();
            $table->smallInteger('payment_status')->default(0);
            $table->smallInteger('shipping_status')->default(0);
            $table->smallInteger('status')->default(0);
            $table->string('transaction_code')->nullable(); // Update when transaction of order is success
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
        Schema::dropIfExists('tb_orders');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbOrderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_order_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->string('transaction_code');
            $table->string('transaction_status');
            $table->string('transaction_response');
            $table->double('amount', 20, 2);
            $table->json('json_params')->nullable(); // Store all response from payment gateway
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
        Schema::dropIfExists('tb_order_transactions');
    }
}

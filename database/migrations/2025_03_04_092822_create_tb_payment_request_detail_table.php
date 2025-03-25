<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPaymentRequestDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_payment_request_detail', function (Blueprint $table) {
            $table->id();
            $table->date('date_arise');
            $table->string('doc_number')->nullable();
            $table->text('content')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('number_times')->nullable();
            $table->json('json_params')->nullable();
            $table->integer('price_vnd')->default(0);
            $table->integer('price_euro')->default(0);
            $table->integer('money_vnd')->default(0);
            $table->integer('money_euro')->default(0);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('tb_payment_request_detail');
    }
}

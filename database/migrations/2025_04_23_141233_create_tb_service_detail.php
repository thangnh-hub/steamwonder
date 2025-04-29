<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbServiceDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_service_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('tb_service');
            $table->decimal('price', 10, 2)->default(0)->comment('Số tiền/đơn giá');
            $table->double('quantity', 10, 2)->default(1)->comment('Số lượng áp dụng');
            $table->dateTime('start_at')->nullable()->comment('Thời gian bắt đầu áp dụng');
            $table->dateTime('end_at')->nullable()->comment('Thời gian kết thúc áp dụng');
            $table->json('json_params')->nullable();
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
        Schema::dropIfExists('tb_service_detail');
    }
}

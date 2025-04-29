<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPaymentCycle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_payment_cycle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained('tb_areas');
            $table->string('name', 255)->comment('1 tháng, 3 tháng, 6 tháng, 12 tháng');
            $table->integer('months')->default(1)->comment('Số tháng');
            $table->boolean('is_default')->default(false);
            $table->json('json_params')->nullable()->comment('Cần lưu thêm thông tin cấu hình các dịch vụ được giảm trừ kèm discount theo dịch vụ đó tương ứng với từng chu kỳ');
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
        Schema::dropIfExists('tb_payment_cycle');
    }
}

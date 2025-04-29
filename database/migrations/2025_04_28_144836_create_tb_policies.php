<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPolicies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained('tb_areas');
            $table->string('code', 50)->comment('Mã chính sách');
            $table->string('name', 255)->comment('Tên chính sách');
            $table->text('description')->nullable();
            $table->json('json_params')->nullable()->comment('Lưu thông tin cấu hình các giảm trừ theo mỗi dịch vụ, > 100 mặc định là đơn vị tiền tệ, <= 100 là %');
            $table->string('status')->default('active')->comment('Trạng thái: active, deactive');
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
        Schema::dropIfExists('tb_policies');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_service', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->foreignId('area_id')->nullable()->constrained('tb_areas');
            $table->foreignId('service_category_id')->nullable()->constrained('tb_service_category');
            $table->foreignId('education_program_id')->nullable()->constrained('tb_education_programs');
            $table->foreignId('education_age_id')->nullable()->constrained('tb_education_ages');
            $table->boolean('is_attendance')->default(false)->comment('Tính theo điểm danh hoặc Không theo điểm danh');
            $table->boolean('is_default')->default(false)->nullable()->comment('Dịch vụ mặc định cho lớp học');
            $table->string('service_type', 100)->nullable()->comment('Loại dịch vụ ví dụ: Thu theo chu kỳ (năm/tháng), hoặc chỉ thu 1 lần...');
            $table->json('json_params')->nullable();
            $table->string('status', 50)->nullable()->default('active');
            $table->integer('iorder')->default(0);
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
        Schema::dropIfExists('tb_service');
    }
}

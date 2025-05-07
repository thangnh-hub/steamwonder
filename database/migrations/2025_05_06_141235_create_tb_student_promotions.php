<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbStudentPromotions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_student_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('tb_students');
            $table->foreignId('promotion_id')->constrained('tb_promotions');
            $table->dateTime('time_start')->comment('Ngày bắt đầu được hưởng khuyến mãi');
            $table->dateTime('time_end')->comment('Ngày kết thúc được hưởng khuyến mãi');
            $table->string('status')->default('active')->comment('Trạng thái: active / deactive');
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
        Schema::dropIfExists('tb_student_promotions');
    }
}

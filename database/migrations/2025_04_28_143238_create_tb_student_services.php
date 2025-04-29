<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbStudentServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_student_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('tb_students');
            $table->foreignId('service_id')->constrained('tb_service');
            $table->foreignId('payment_cycle_id')->constrained('tb_payment_cycle');
            $table->string('status')->default('active')->comment('Trạng thái dịch vụ: active, cancelled');
            $table->dateTime('cancelled_at')->nullable();
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
        Schema::dropIfExists('tb_student_services');
    }
}

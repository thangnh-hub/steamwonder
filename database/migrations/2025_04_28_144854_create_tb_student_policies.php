<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbStudentPolicies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_student_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('tb_students');
            $table->foreignId('policy_id')->constrained('tb_policies');
            $table->json('json_params')->nullable();
            $table->string('status')->nullable()->default('active');
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
        Schema::dropIfExists('tb_student_policies');
    }
}

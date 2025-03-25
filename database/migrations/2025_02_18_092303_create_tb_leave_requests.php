<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbLeaveRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('admins');
            $table->string('is_type')->default('paid'); // paid / unpaid tương ứng có lương, không lương
            $table->date('start_date');
            $table->date('end_date');
            $table->float('total_days', 8, 2);
            $table->text('reason')->nullable();
            $table->string('status')->default('pending'); // ENUM('pending', 'approved', 'rejected', 'cancelled')
            $table->json('json_params')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('approver_id')->nullable()->constrained('admins');
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
        Schema::dropIfExists('tb_leave_requests');
    }
}

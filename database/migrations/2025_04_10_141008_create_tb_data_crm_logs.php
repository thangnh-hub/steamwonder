<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDataCrmLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_data_crm_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_crm_id')->nullable()->constrained('tb_data_crms');
            $table->string('status', 50)->nullable();
            $table->text('content')->nullable();
            $table->json('json_params')->nullable();
            $table->dateTime('consulted_at')->nullable();
            $table->string('result', 50)->nullable();
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
        Schema::dropIfExists('tb_data_crm_logs');
    }
}

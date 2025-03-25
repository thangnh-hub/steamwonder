<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDormitoryMusterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_dormitory_muster', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_dormitory');
            $table->bigInteger('id_user');
            $table->date('time_muster');
            $table->string('status',255)->default('present');
            $table->bigInteger('admin_created_id');
            $table->bigInteger('admin_updated_id');
            $table->json('json_params')->nullable();
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
        Schema::dropIfExists('tb_dormitory_muster');
    }
}

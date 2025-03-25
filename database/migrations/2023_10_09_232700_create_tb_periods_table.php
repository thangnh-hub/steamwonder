<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_periods', function (Blueprint $table) {
            $table->id();
            $table->string('start_time')->nullable()->comment('thời gian bắt đầu');
            $table->string('end_time')->nullable()->comment('thời gian kết thúc');
            $table->integer('iorder')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('tb_periods');
    }
}

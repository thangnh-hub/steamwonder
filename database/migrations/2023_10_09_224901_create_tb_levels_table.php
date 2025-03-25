<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('tb_subjects')->comment('ID bộ môn');
            $table->string('name')->comment('Tên trình độ');
            $table->string('code')->comment('Mã trình độ');
            $table->double('credit', 8, 2)->nullable()->comment('Số tín chỉ');
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
        Schema::dropIfExists('tb_levels');
    }
}

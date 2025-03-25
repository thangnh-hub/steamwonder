<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên bộ môn');
            $table->string('status')->default('active')->comment('Trạng thái');
            $table->json('json_params')->nullable()->comment('This is comment');
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
        Schema::dropIfExists('tb_subjects');
    }
}

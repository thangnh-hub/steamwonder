<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbEntryLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_entry_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên trình độ đầu vào');
            $table->string('code')->comment('Mã trình độ đầu vào');
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
        Schema::dropIfExists('tb_entry_levels');
    }
}

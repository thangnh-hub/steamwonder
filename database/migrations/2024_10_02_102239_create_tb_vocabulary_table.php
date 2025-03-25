<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbVocabularyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_vocabulary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('prefix')->nullable();
            $table->string('transcription')->nullable();
            $table->string('meaning')->nullable();
            $table->string('image')->nullable();
            $table->json('json_params')->nullable();
            $table->timestamps();  // Tự động thêm cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_vocabulary');
    }
}

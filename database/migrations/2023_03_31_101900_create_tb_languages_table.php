<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_languages', function (Blueprint $table) {
            $table->id();
            $table->string('lang_name');
            $table->string('lang_locale');
            $table->string('lang_code');
            $table->string('flag')->nullable();
            $table->boolean('is_default')->default(false);
            $table->integer('iorder')->nullable();
            $table->string('status')->default('active');
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
        Schema::dropIfExists('tb_languages');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDecisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_decisions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('signer');
            $table->dateTime('sign_date')->nullable()->comment('Ngày ký');
            $table->dateTime('active_date')->nullable()->comment('Ngày hiệu lực');
            $table->string('file_name')->nullable()->comment('File quyết định');
            $table->string('is_type')->nullable()->comment('Loại quyết định');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('tb_decisions');
    }
}

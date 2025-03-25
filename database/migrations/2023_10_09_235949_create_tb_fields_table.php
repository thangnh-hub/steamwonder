<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên lĩnh vực');
            $table->string('code')->comment('Mã lĩnh vực');
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
        Schema::dropIfExists('tb_fields');
    }
}

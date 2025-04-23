<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbEducationPrograms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_education_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained('tb_areas');
            $table->string('name', 255);
            $table->json('json_params')->nullable();
            $table->string('status')->nullable()->default('active');
            $table->integer('iorder')->default(0);
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
        Schema::dropIfExists('tb_education_programs');
    }
}

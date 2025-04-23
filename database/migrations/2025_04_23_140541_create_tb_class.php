<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained('tb_areas');
            $table->string('code', 50);
            $table->string('name', 255);
            $table->integer('slot')->default(0);
            $table->foreignId('room_id')->nullable()->constrained('tb_rooms');
            $table->foreignId('education_program_id')->nullable()->constrained('tb_education_programs');
            $table->foreignId('education_age_id')->nullable()->constrained('tb_education_ages');
            $table->boolean('is_lastyear')->default(false);
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
        Schema::dropIfExists('tb_class');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_department', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->nullable();
            $table->string('name', 255)->nullable();
            $table->foreignId('area_id')->nullable()->constrained('tb_areas');
            $table->json('json_params')->nullable();
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
        Schema::dropIfExists('tb_department');
    }
}

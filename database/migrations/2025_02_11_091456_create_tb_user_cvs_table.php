<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUserCvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_user_cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('admins');
            $table->string('is_type')->nullable();
            $table->string('cv_code')->nullable();
            $table->string('cv_title')->nullable();
            $table->json('json_params')->nullable();
            $table->string('cv_template_code')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('time_refresh')->nullable();
            $table->boolean('is_top')->default(false);
            $table->integer('count_visited')->default(0);
            $table->boolean('is_main')->default(false);
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
        Schema::dropIfExists('tb_user_cvs');
    }
}

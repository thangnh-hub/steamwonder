<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCmsPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cms_posts', function (Blueprint $table) {
            $table->id();
            $table->string('is_type')->nullable()->default('post');
            $table->string('name');
            $table->string('alias');
            $table->json('json_params')->nullable();
            $table->string('image')->nullable();
            $table->string('image_thumb')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('count_visited')->default(0);
            $table->integer('count_comment')->default(0);
            $table->integer('iorder')->nullable();
            $table->string('status')->default('published');
            $table->dateTime('public_time')->nullable();
            $table->double('price', 20, 2)->nullable();
            $table->double('price_old', 20, 2)->nullable();
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
        Schema::dropIfExists('tb_cms_posts');
    }
}

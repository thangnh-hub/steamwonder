<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTbScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_scores', function (Blueprint $table) {
            $table->integer('score_listen')->nullable()->comment('Comment for score_listen');
            $table->integer('score_speak')->nullable()->comment('Comment for score_speak');
            $table->integer('score_read')->nullable()->comment('Comment for score_read');
            $table->integer('score_write')->nullable()->comment('Comment for score_write');
            $table->string('status')->nullable()->comment('Comment for status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_scores', function (Blueprint $table) {
            $table->dropColumn('score_listen');
            $table->dropColumn('score_speak');
            $table->dropColumn('score_read');
            $table->dropColumn('score_write');
            $table->dropColumn('status');
        });
    }
}

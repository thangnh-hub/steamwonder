<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTbAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->nullable()->comment('Lớp học ID')->after('user_id');
            $table->boolean('is_homework')->default(false)->comment('Is Homework');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_attendances', function (Blueprint $table) {
            $table->dropColumn('class_id');
            $table->dropColumn('is_homework');
        });
    }
}

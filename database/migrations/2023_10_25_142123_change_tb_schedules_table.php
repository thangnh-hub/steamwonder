<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTbSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->nullable()->comment('GV ID')->after('area_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_schedules', function (Blueprint $table) {
            $table->dropColumn('teacher_id');
        });
    }
}

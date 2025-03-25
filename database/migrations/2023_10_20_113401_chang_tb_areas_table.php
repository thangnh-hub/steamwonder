<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangTbAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_areas', function (Blueprint $table) {
            $table->string('code')->after('name')->nullable()->comment('Mã khu vực');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_areas', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}

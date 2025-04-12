<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTbParents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_parents', function (Blueprint $table) {
            $table->foreignId('area_id')->nullable()->constrained('tb_areas')->after('id');
            $table->foreignId('admission_id')->nullable()->constrained('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_parents', function (Blueprint $table) {
            $table->dropColumn([
                'area_id', 
                'admission_id'
            ]);
        });
    }
}

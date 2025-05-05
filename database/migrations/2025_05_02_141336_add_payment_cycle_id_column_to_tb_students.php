<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentCycleIdColumnToTbStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_students', function (Blueprint $table) {
            $table->foreignId('payment_cycle_id')->nullable()->constrained('tb_payment_cycle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_students', function (Blueprint $table) {
            $table->dropColumn([
                'payment_cycle_id'
            ]);
        });
    }
}

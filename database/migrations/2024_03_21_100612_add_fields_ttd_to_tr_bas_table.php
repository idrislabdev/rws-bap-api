<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsTtdToTrBasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_bas', function (Blueprint $table) {
            $table->string('paraf_wholesale')->nullable();
            $table->foreign('paraf_wholesale')->references('id')->on('ma_penggunas');
            $table->json('paraf_wholesale_data')->nullable();
            $table->string('manager_wholesale')->nullable();
            $table->foreign('manager_wholesale')->references('id')->on('ma_penggunas');
            $table->json('manager_wholesale_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_bas', function (Blueprint $table) {
            //
        });
    }
}

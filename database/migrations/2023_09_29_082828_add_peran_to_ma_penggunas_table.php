<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeranToMaPenggunasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ma_penggunas', function (Blueprint $table) {
            $table->string('peran', 100)->nullable();
            $table->foreign('peran')->references('id')->on('ma_perans');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ma_penggunas', function (Blueprint $table) {
            //
        });
    }
}

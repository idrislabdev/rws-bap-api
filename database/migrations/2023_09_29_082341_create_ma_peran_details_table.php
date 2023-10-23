<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaPeranDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_peran_details', function (Blueprint $table) {
            $table->string('peran_id', 100);
            $table->foreign('peran_id')->references('id')->on('ma_perans');
            $table->string('hak_akses_id', 100);
            $table->foreign('hak_akses_id')->references('id')->on('ma_hak_akseses');
            $table->primary(['peran_id', 'hak_akses_id'], 'peran_hak_akses_id');
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
        Schema::dropIfExists('ma_peran_details');
    }
}

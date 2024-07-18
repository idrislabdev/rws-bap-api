<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrHistoryPengajuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_history_pengajuans', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nama');
            $table->integer('batch');
            $table->dateTime('tanggal');
            $table->enum('aplikasi', ['starclick_ncx', 'ncx_cons']);
            $table->enum('status', ['process', 'finished']);
            $table->string('nota_dinas_url')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->foreign('created_by')->references('id')->on('ma_penggunas');
            $table->json('created_by_data')->nullable();
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
        Schema::dropIfExists('tr_history_pengajuans');
    }
}

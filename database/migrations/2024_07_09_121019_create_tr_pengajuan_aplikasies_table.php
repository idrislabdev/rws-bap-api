<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrPengajuanAplikasiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_pengajuan_aplikasies', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->dateTime('tanggal');
            $table->enum('jenis_aplikasi', ['STARCLICK-NCX', 'NCX-CONS']);
            $table->enum('status', ['PROSES', 'SELESAI']);
            $table->string('dibuat_oleh', 100)->nullable();
            $table->foreign('dibuat_oleh')->references('id')->on('ma_penggunas');
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
        Schema::dropIfExists('tr_pengajuan_aplikasies');
    }
}

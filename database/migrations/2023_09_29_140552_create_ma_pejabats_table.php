<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaPejabatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_pejabats', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreign('id')->references('id')->on('ma_penggunas');
            $table->string('nama');
            $table->string('nik')->nullable();
            $table->string('jabatan');
            $table->string('lokasi_kerja');
            $table->string('ttd_image');
            $table->enum('status', ['AKTIF', 'TIDAK-AKTIF']);
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
        Schema::dropIfExists('ma_pejabats');
    }
}

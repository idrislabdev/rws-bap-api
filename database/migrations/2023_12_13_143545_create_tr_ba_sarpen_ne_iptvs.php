<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrBaSarpenNeIptvs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_ba_sarpen_ne_iptvs', function (Blueprint $table) {
            $table->foreign('sarpen_id')->references('id')->on('tr_ba_sarpens');
            $table->string('sarpen_id');
            $table->integer('no');
            $table->primary(['sarpen_id', 'no']);
            $table->string('nama_perangkat');
            $table->string('type_perangkat')->nullable();
            $table->string('merk')->nullable();
            $table->string('model')->nullable();
            $table->string('spesifikasi_teknis')->nullable();
            $table->string('rack')->nullable();
            $table->string('ruang_rack')->nullable();
            $table->string('lantai')->nullable();
            $table->string('space_lokasi')->nullable();
            $table->string('power_catu_daya')->nullable();
            $table->string('catuan_ac_dc')->nullable();
            $table->string('ruangan_share_dedicated')->nullable();
            $table->string('iptv_platform')->nullable();
            $table->string('jumlah_perangkat')->nullable();
            $table->string('validasi')->nullable();
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
        Schema::dropIfExists('tr_ba_sarpen_ne_iptvs');
    }
}

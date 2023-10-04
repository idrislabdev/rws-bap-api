<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrBaSarpensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_ba_sarpens', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('no_dokumen')->unique();
            $table->string('no_dokumen_klien')->nullable();
            $table->dateTime('tanggal_buat');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('alamat')->nullable();
            $table->enum('group', ['TELKOM', 'OTHER']);
            $table->string('site')->nullable();
            $table->foreign('site')->references('id')->on('ma_sites');
            $table->string('sto')->nullable();
            $table->foreign('sto')->references('id')->on('ma_stos');
            $table->string('nomor_order')->nullable();
            $table->string('regional')->nullable();
            $table->string('klien')->nullable();
            $table->json('klien_data')->nullable();
            $table->foreign('klien')->references('id')->on('ma_olo_kliens');
            $table->string('paraf')->nullable();
            $table->foreign('paraf')->references('id')->on('ma_pejabats');
            $table->json('paraf_data')->nullable();
            $table->string('pejabat')->nullable();
            $table->foreign('pejabat')->references('id')->on('ma_pejabats');
            $table->json('pejabat_data')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['DRAFT', 'PROPOSED', 'REJECTED', 'APPROVED']);
            $table->json('setting');
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
        Schema::dropIfExists('tr_ba_sarpens');
    }
}

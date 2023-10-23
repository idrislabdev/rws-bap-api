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
            $table->string('no_dokumen')->nullable()->unique();
            $table->string('no_dokumen_klien')->nullable();
            $table->dateTime('tanggal_buat');
            $table->enum('group', ['TELKOM', 'OTHER']);
            $table->string('type');
            $table->string('site')->nullable();
            $table->foreign('site')->references('id')->on('ma_sites');
            $table->string('sto')->nullable();
            $table->foreign('sto')->references('id')->on('ma_stos');
            $table->string('nama_site')->nullable();
            $table->string('nama_sto')->nullable();
            $table->string('nomor_order')->nullable();
            $table->string('alamat')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('regional')->nullable();
            $table->string('klien')->nullable();
            $table->json('klien_data')->nullable();
            $table->foreign('klien')->references('id')->on('ma_olo_kliens');
            $table->string('manager_witel')->nullable();
            $table->foreign('manager_witel')->references('id')->on('ma_penggunas');
            $table->json('manager_witel_data')->nullable();
            $table->string('paraf_wholesale');
            $table->foreign('paraf_wholesale')->references('id')->on('ma_penggunas');
            $table->json('paraf_wholesale_data')->nullable();
            $table->string('manager_wholesale');
            $table->foreign('manager_wholesale')->references('id')->on('ma_penggunas');
            $table->json('manager_wholesale_data')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'proposed', 'rejected', 'ttd_witel', 'paraf_wholesale', 'ttd_wholesale', 'finished']);
            $table->json('setting');
            $table->string('created_by');
            $table->foreign('created_by')->references('id')->on('ma_penggunas');
            $table->string('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('ma_penggunas');
            $table->string('rejected_by')->nullable();
            $table->foreign('rejected_by')->references('id')->on('ma_penggunas');
            $table->string('site_witel')->nullable();
            $table->string('dokumen_sirkulir')->nullable();
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

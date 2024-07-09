<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrPengajuanAplikasiDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_pengajuan_aplikasi_details', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_account');
            $table->foreign('user_account')->references('id')->on('ma_user_accounts');
            $table->string('site_witel');
            $table->enum('jenis_aplikasi', ['STARCLICK-NCX', 'NCX-CONS']);
            $table->json('detail_aplikasi');
            $table->enum('jenis_pengajuan', ['BARU', 'REAKTIVASI', 'TAMBAH-FITUR', 'HAPUS']);
            $table->enum('status_pengajuan', ['DIAJUKAN', 'DITERIMA', 'DITOLAK', 'PROSES', 'SELESAI']);
            $table->dateTime('tanggal_pengajuan');
            $table->string('diajukan_oleh', 100)->nullable();
            $table->foreign('diajukan_oleh')->references('id')->on('ma_penggunas');
            $table->string('ditolak_oleh', 100)->nullable();
            $table->foreign('ditolak_oleh')->references('id')->on('ma_penggunas');
            $table->string('diterima_oleh', 100)->nullable();
            $table->foreign('diterima_oleh')->references('id')->on('ma_penggunas');
            $table->string('diproses_oleh', 100)->nullable();
            $table->foreign('diproses_oleh')->references('id')->on('ma_penggunas');
            $table->text('catatan_ditolak')->nullable();
            $table->string('pengajuan_id')->nullable();
            $table->foreign('pengajuan_id')->references('id')->on('tr_pengajuan_aplikasies');
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
        Schema::dropIfExists('tr_pengajuan_aplikasi_details');
    }
}

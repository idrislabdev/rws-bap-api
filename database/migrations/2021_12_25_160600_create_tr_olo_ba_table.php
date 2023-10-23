<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrOloBaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_olo_bas', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('no_dokumen_baut', 100)->nullable()->unique();
            $table->string('no_dokumen_bast', 100)->nullable()->unique();
            $table->date('tgl_dokumen');
            $table->string('klien_id', 100);         
            $table->string('klien_penanggung_jawab_baut', 100);
            $table->string('klien_jabatan_penanggung_jawab_baut', 100);
            $table->string('klien_lokasi_kerja_baut', 100);
            $table->string('klien_nama_baut', 100);
            $table->string('dibuat_oleh', 100);
            $table->boolean('status_approval');
            $table->string('approved_by', 100)->nullable();
            $table->foreign('approved_by')->references('id')->on('ma_penggunas');
            $table->string('jenis_order_id', 100);
            $table->string('jenis_order', 50);
            $table->foreign('jenis_order_id')->references('id')->on('ma_olo_jenis_orders');
            $table->foreign('dibuat_oleh')->references('id')->on('ma_penggunas');
            $table->foreign('klien_id')->references('id')->on('ma_olo_kliens');
            
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
        Schema::dropIfExists('tr_olo_bas');
    }
}

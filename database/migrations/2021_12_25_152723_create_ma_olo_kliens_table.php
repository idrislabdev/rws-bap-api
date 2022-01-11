<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaOloKliensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_olo_kliens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('nama_perusahaan', 150);
            $table->string('alamat_perusahaan', 150);
            $table->string('nama_penanggung_jawab');
            $table->string('jabatan_penanggung_jawab');
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
        Schema::dropIfExists('ma_olo_kliens');
    }
}

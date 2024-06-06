<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaNoDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_nomor_dokumens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('no_dokumen', 100)->unique();
            $table->enum('tipe_dokumen', ['NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'OLO_BAUT', 'OLO_BAST']);
            $table->date('tgl_dokumen');
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
        Schema::dropIfExists('ma_nomor_dokumens');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddEnumToMaNomorDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ma_nomor_dokumens', function (Blueprint $table) {
            DB::statement("ALTER TABLE ma_nomor_dokumens MODIFY tipe_dokumen ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI', 'OLO_BAUT', 'OLO_BAST', 'CNOP_OTHER_BAST', 'CNOP_OTHER_BAUT', 'SARPEN') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ma_nomor_dokumens', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddEnumToWorkOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE tr_wos MODIFY tipe_ba ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI') NOT NULL");
        DB::statement("ALTER TABLE tr_wo_sites MODIFY tipe_ba ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI') NOT NULL");
        DB::statement("ALTER TABLE ma_nomor_dokumens MODIFY tipe_dokumen ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI', 'OLO_BAUT', 'OLO_BAST', 'CNOP_OTHER_BAST', 'CNOP_OTHER_BAUT') NOT NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE tr_wos MODIFY tipe_ba ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE') NOT NULL");
        DB::statement("ALTER TABLE tr_wo_sites MODIFY tipe_ba ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE') NOT NULL");
        DB::statement("ALTER TABLE ma_nomor_dokumens MODIFY tipe_dokumen ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'OLO_BAUT', 'OLO_BAST') NOT NULL");

    }
}

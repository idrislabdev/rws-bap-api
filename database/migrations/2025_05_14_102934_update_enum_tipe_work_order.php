<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateEnumTipeWorkOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE tr_wos MODIFY tipe_ba ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI', 'FRONTHAUL') NOT NULL");
        DB::statement("ALTER TABLE tr_wo_sites MODIFY tipe_ba ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI', 'FRONTHAUL') NOT NULL");
        DB::statement("ALTER TABLE ma_nomor_dokumens MODIFY tipe_dokumen ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI', 'OLO_BAUT', 'OLO_BAST', 'CNOP_OTHER_BAST', 'CNOP_OTHER_BAUT', 'SARPEN', 'BAKES', 'PKS', 'FRONTHAUL') NOT NULL");
        DB::statement("ALTER TABLE tr_bas MODIFY tipe ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI', 'FRONTHAUL') NOT NULL");
        DB::statement("ALTER TABLE tr_wo_site_images MODIFY tipe ENUM('KONFIGURASI', 'TOPOLOGI', 'CAPTURE_TRAFIK', 'LV', 'QC', 'NODE_1', 'NODE_2', 'OTDR') NOT NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}

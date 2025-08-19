<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeEnumTipeBaToTrOloBaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE tr_olo_bas MODIFY `tipe_ba` ENUM('OLO', 'CNOP', 'OLO_TIF') NOT NULL");            
        DB::statement("ALTER TABLE draft_olo_bas MODIFY `tipe_ba` ENUM('OLO', 'CNOP', 'OLO_TIF') NOT NULL");      
        DB::statement("ALTER TABLE ma_nomor_dokumens MODIFY tipe_dokumen ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI', 'OLO_BAUT', 'OLO_BAST', 'CNOP_OTHER_BAST', 'CNOP_OTHER_BAUT', 'SARPEN', 'BAKES', 'PKS', 'FRONTHAUL', 'OLO_TIF_BAUT', 'OLO_TIF_BAST') NOT NULL");

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

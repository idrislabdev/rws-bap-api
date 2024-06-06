<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddEnumToTrBas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE tr_bas MODIFY tipe ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE', 'RELOKASI') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE tr_bas MODIFY tipe ENUM('NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE') NOT NULL");

    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTglDeactivateToTrWoSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_wo_sites', function (Blueprint $table) {
            $table->date('tgl_on_air')->nullable()->change();
            $table->date('tgl_deactivate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_wo_sites', function (Blueprint $table) {
        });
    }
}

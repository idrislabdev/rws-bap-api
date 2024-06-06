<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTrWoSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_wo_sites', function (Blueprint $table) {
            $table->string('alamat_awal')->nullable();
            $table->string('latitutde_awal')->nullable();
            $table->string('longitude_awal')->nullable();
            $table->string('badnwidth_awal')->nullable();

            $table->string('alamat_baru')->nullable();
            $table->string('latitutde_baru')->nullable();
            $table->string('longitude_baru')->nullable();
            $table->string('badnwidth_baru')->nullable();
            
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
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTrOloBaDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_olo_ba_details', function (Blueprint $table) {
            $table->string('dasar_order', 100)->nullable();
            $table->string('site_id', 100)->nullable();
            $table->string('site_name', 100)->nullable();
            $table->string('alamat_tujuan', 100)->nullable();
            $table->string('sn_perangkat', 100)->nullable();
            $table->integer('bandwidth_mbps_akhir')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_olo_ba_details', function (Blueprint $table) {

        });
    }
}

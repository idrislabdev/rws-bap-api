<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTtdToDraftOloBasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('draft_olo_bas', function (Blueprint $table) {
            $table->string('ttd_nama')->nullable();
            $table->string('ttd_jabatan')->nullable();
            $table->string('ttd_lokasi_kerja')->nullable();
            $table->string('ttd_perusahaan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('draft_olo_bas', function (Blueprint $table) {
            //
        });
    }
}

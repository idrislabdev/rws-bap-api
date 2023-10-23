<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPeranToMaPenggunasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ma_penggunas', function (Blueprint $table) {
            $table->string('peran')->nullable();
            $table->foreign('peran')->references('id')->on('ma_perans');
            $table->string('jabatan')->nullable();
            $table->string('nik')->nullable();
            $table->string('lokasi_kerja')->nullable();
            $table->string('ttd_image')->nullable();
        });

        DB::statement("ALTER TABLE ma_penggunas MODIFY role ENUM('ROOT','ADMIN', 'RWS', 'WITEL', 'MSO') NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ma_penggunas', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrBaSarpenRuangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_ba_sarpen_ruangans', function (Blueprint $table) {
            $table->foreign('sarpen_id')->references('id')->on('tr_ba_sarpens');
            $table->string('sarpen_id');
            $table->integer('no');
            $table->primary(['sarpen_id', 'no']);
            $table->string('nama_ruangan');
            $table->string('peruntukan_ruangan')->nullable();
            $table->string('bersama_tersendiri')->nullable();
            $table->string('terkondisi')->nullable();
            $table->string('status_kepemilikan_ac')->nullable();
            $table->string('panjang_meter')->nullable();
            $table->string('lebar_meter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_ba_sarpen_ruangans');
    }
}

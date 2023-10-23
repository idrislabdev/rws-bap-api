<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrBaSarpenServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_ba_sarpen_services', function (Blueprint $table) {
            $table->foreign('sarpen_id')->references('id')->on('tr_ba_sarpens');
            $table->string('sarpen_id');
            $table->integer('no');
            $table->primary(['sarpen_id', 'no']);
            $table->string('nama_service');
            $table->string('port_pe')->nullable();
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('tr_ba_sarpen_services');
    }
}

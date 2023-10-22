<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaSarpenTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_sarpen_templates', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nama')->unique();
            $table->enum('group', ['TELKOM', 'OTHER']);
            $table->enum('sto_site', ['STO', 'SITE', 'NO_ORDER']);
            // $table->boolean('no_dokumen_client');
            $table->boolean('tower');
            $table->boolean('rack');
            $table->boolean('ruangan');
            $table->boolean('lahan');
            $table->boolean('catu_daya_mcb');
            $table->boolean('catu_daya_genset');
            $table->boolean('service');
            $table->boolean('akses');
            $table->boolean('catatan');
            $table->string('paraf_wholesale');
            $table->foreign('paraf_wholesale')->references('id')->on('ma_penggunas');
            $table->string('manager_wholesale');
            $table->foreign('manager_wholesale')->references('id')->on('ma_penggunas');
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
        Schema::dropIfExists('ma_sarpen_templates');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrWoSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_wo_sites', function (Blueprint $table) {
            $table->string('wo_id', 100);
            $table->integer('wo_site_id');
            $table->foreign('wo_id')->references('id')->on('tr_wos')->onDelete('cascade');
            $table->string('site_id', 25);
            $table->string('site_name', 50);
            $table->string('site_witel', 50);
            $table->string('tsel_reg', 50);
            $table->date('tgl_on_air');
            $table->integer('data_2g');
            $table->integer('data_3g');
            $table->integer('data_4g');
            $table->integer('jumlah');
            $table->string('program', 25);
            $table->primary(['wo_id', 'wo_site_id']);
            $table->enum('status', ['OGP', 'OA']);
            $table->boolean('progress');
            $table->string('ba_id', 100)->nullable();
            $table->foreign('ba_id')->references('id')->on('tr_bas');
            $table->string('dibuat_oleh', 100);
            $table->foreign('dibuat_oleh')->references('id')->on('ma_penggunas');
            $table->enum('tipe_ba', ['NEW_LINK', 'DUAL_HOMING', 'COMBAT_TEMPORARY', 'DISMANTLE', 'UPGRADE']);
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
        Schema::dropIfExists('tr_wo_sites');
    }
}

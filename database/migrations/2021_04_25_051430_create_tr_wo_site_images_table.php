<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrWoSiteImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_wo_site_images', function (Blueprint $table) {
            $table->string('wo_id', 100);
            $table->integer('wo_site_id');
            $table->integer('id');
            $table->foreign(['wo_id', 'wo_site_id'])->references(['wo_id', 'wo_site_id'])->on('tr_wo_sites')->onDelete('cascade');
            $table->enum('tipe', ['KONFIGURASI', 'TOPOLOGI', 'CAPTURE_TRAFIK']);
            $table->string('image_url');
            $table->primary(['wo_id', 'wo_site_id','id'], 'ba_site_konfigurasi');
            $table->string('dibuat_oleh', 100);
            $table->foreign('dibuat_oleh')->references('id')->on('ma_penggunas');
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
        Schema::dropIfExists('tr_wo_site_images');
    }
}

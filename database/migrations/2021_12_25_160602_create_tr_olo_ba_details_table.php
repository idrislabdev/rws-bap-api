<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrOloBaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_olo_ba_details', function (Blueprint $table) {
            $table->string('olo_ba_id', 100);
            $table->integer('id');
            $table->foreign('olo_ba_id')->references('id')->on('tr_olo_bas')->onDelete('cascade');
            $table->string('ao_sc_order',100);
            $table->string('sid',100);
            $table->string('produk_id', 100);
            $table->string('produk', 100);
            $table->integer('bandwidth_mbps')->nullable();
            $table->string('add_on', 150)->nullable();
            $table->string('jenis_order_id', 100);
            $table->string('jenis_order', 50);
            $table->string('alamat_instalasi', 100);
            $table->date('tgl_order');;
            $table->primary(['olo_ba_id', 'id'], 'olo_ba_detail_id');
            $table->string('dibuat_oleh', 100);
            $table->foreign('dibuat_oleh')->references('id')->on('ma_penggunas');
            $table->foreign('produk_id')->references('id')->on('ma_olo_produks');
            $table->foreign('jenis_order_id')->references('id')->on('ma_olo_jenis_orders');
        
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
        Schema::dropIfExists('tr_olo_ba_details');
    }
}

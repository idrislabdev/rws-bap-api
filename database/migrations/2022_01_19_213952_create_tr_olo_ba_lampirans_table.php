<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrOloBaLampiransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_olo_ba_lampirans', function (Blueprint $table) {
            $table->string('olo_ba_id', 100);
            $table->integer('id');
            $table->foreign('olo_ba_id')->references('id')->on('tr_olo_bas')->onDelete('cascade');
            $table->string('url');
            $table->string('dibuat_oleh', 100);
            $table->foreign('dibuat_oleh')->references('id')->on('ma_penggunas');
            $table->primary(['olo_ba_id', 'id'], 'olo_ba_detail_id');
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
        Schema::dropIfExists('tr_olo_ba_ba_lampirans');
    }
}

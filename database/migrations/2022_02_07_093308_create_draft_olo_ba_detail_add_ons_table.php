<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftOloBaDetailAddOnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_olo_ba_detail_add_ons', function (Blueprint $table) {
            $table->string('olo_ba_id', 100);
            $table->integer('id');
            $table->foreign(['olo_ba_id', 'id'])->references(['olo_ba_id', 'id'])->on('draft_olo_ba_details')->onDelete('cascade');
            $table->string('add_on_id', 100);
            $table->string('nama_add_on', 100);
            $table->string('satuan');
            $table->double('jumlah');
            $table->foreign('add_on_id')->references('id')->on('ma_olo_jenis_add_ons');
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
        Schema::dropIfExists('draft_olo_ba_detail_add_ons');
    }
}

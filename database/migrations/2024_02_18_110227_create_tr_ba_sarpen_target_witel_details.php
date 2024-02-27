<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrBaSarpenTargetWitelDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_ba_sarpen_target_witel_details', function (Blueprint $table) {
            $table->string('sarpen_target_detail_id');
            $table->integer('detail_no');
            $table->foreign(['sarpen_target_detail_id', 'detail_no'], 'sarpen_target_detail_foreign_id')->references(['sarpen_target_id', 'no'])->on('tr_ba_sarpen_target_witels')->onDelete('cascade');
            $table->integer('no');
            $table->primary(['sarpen_target_detail_id','detail_no', 'no'], 'sarpen_target_witel_detail_id');
            $table->enum('tipe', ['STO', 'SITE', 'OTHER']);
            $table->string('kode');
            $table->string('alamat')->nullable();
            $table->string('no_dokumen')->nullable()->unique();
            $table->string('user_witel')->nullable();
            $table->foreign('user_witel')->references('id')->on('ma_penggunas');
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
        Schema::dropIfExists('tr_ba_sarpen_target_witel_details');
    }
}

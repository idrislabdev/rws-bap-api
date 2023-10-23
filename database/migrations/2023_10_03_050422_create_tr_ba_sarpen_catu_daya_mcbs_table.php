<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrBaSarpenCatuDayaMcbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_ba_sarpen_catu_daya_mcbs', function (Blueprint $table) {
            $table->foreign('sarpen_id')->references('id')->on('tr_ba_sarpens');
            $table->string('sarpen_id');
            $table->integer('no');
            $table->primary(['sarpen_id', 'no']);
            $table->string('peruntukan');
            $table->string('mcb_amp')->nullable();
            $table->string('jumlah_phase')->nullable();
            $table->string('voltage')->nullable();
            $table->string('catatan')->nullable();
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
        Schema::dropIfExists('tr_ba_sarpen_catu_daya_mcbs');
    }
}

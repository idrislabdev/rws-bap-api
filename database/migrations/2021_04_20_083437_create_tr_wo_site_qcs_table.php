<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrWoSiteQcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_wo_site_qcs', function (Blueprint $table) {
            $table->string('wo_id', 100);
            $table->integer('wo_site_id');
            $table->enum('tipe', ['packet_loss', 'latency']);
            $table->foreign(['wo_id', 'wo_site_id'])->references(['wo_id', 'wo_site_id'])->on('tr_wo_sites')->onDelete('cascade');
            $table->string('day1', 25);
            $table->string('day2', 25);
            $table->string('day3', 25);
            $table->string('day4', 25);
            $table->string('day5', 25);
            $table->string('day6', 25);
            $table->date('day1_date');
            $table->date('day2_date');
            $table->date('day3_date');
            $table->date('day4_date');
            $table->date('day5_date');
            $table->date('day6_date');
            $table->string('result', 25);
            $table->string('pass', 15);
            $table->primary(['wo_id', 'wo_site_id','tipe'], 'wo_site_qcs');
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
        Schema::dropIfExists('tr_wo_site_qcs');
    }
}

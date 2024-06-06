<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrWoSiteLvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_wo_site_lvs', function (Blueprint $table) {
            $table->string('wo_id', 100);
            $table->integer('wo_site_id');
            $table->enum('tipe', ['packet_loss', 'latency']);
            $table->foreign(['wo_id', 'wo_site_id'])->references(['wo_id', 'wo_site_id'])->on('tr_wo_sites')->onDelete('cascade');
            $table->string('hour1', 25);
            $table->string('hour2', 25);
            $table->string('hour3', 25);
            $table->string('hour4', 25);
            $table->string('hour5', 25);
            $table->string('hour1_time', 10);
            $table->string('hour2_time', 10);
            $table->string('hour3_time', 10);
            $table->string('hour4_time', 10);
            $table->string('hour5_time', 10);
            $table->date('day_date');
            $table->string('result', 25);
            $table->string('pass', 15);
            $table->primary(['wo_id', 'wo_site_id','tipe'], 'wo_site_lvs');
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
        Schema::dropIfExists('tr_wo_site_lvs');
    }
}

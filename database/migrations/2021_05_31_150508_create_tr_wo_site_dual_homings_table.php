<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrWoSiteDualHomingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_wo_site_dual_homings', function (Blueprint $table) {
            $table->string('wo_id', 100);
            $table->integer('wo_site_id');
            $table->foreign(['wo_id', 'wo_site_id'])->references(['wo_id', 'wo_site_id'])->on('tr_wo_sites')->onDelete('cascade');
            $table->string('tipe_topologi', 25);
            $table->string('sto_a', 25);
            $table->string('sto_b', 25); 
            $table->string('metro_1', 25);
            $table->string('metro_2', 25);
            $table->string('gpon_1', 25);
            $table->string('gpon_2', 25);
            $table->string('port_otb_1', 25);
            $table->string('port_otb_2', 25);
            $table->string('odc_odp_1', 25);
            $table->string('odc_odp_2', 25);
            $table->string('tipe_modem', 25);
            $table->primary(['wo_id', 'wo_site_id'], 'site_dual_homing');
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
        Schema::dropIfExists('tr_wo_site_dual_homings');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldForFronthaulToTrWoSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_wo_sites', function (Blueprint $table) {
            $table->string('site_id_fh')->nullable();
            $table->string('site_name_fh')->nullable();
            $table->string('core_fh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_wo_sites', function (Blueprint $table) {
            //
        });
    }
}

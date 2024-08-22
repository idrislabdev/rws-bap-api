<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDefaultToMaJabatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ma_jabatans', function (Blueprint $table) {
            $table->json('starclick_ncx_default')->nullable();
            $table->json('ncx_cons_default')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ma_jabatans', function (Blueprint $table) {
            //
        });
    }
}

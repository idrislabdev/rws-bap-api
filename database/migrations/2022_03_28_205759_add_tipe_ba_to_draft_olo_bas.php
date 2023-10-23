<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipeBaToDraftOloBas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('draft_olo_bas', function (Blueprint $table) {
            $table->enum('tipe_ba', ['OLO', 'CNOP'])->default('OLO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('draft_olo_bas', function (Blueprint $table) {
            //
        });
    }
}

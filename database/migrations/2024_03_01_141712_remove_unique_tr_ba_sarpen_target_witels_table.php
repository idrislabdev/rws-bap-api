<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueTrBaSarpenTargetWitelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_ba_sarpen_target_witels', function (Blueprint $table) {
            $table->dropUnique(['witel']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_ba_sarpen_target_witels', function (Blueprint $table) {
            //
        });
    }
}

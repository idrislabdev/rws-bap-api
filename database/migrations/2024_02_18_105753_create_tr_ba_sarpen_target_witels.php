<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrBaSarpenTargetWitels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_ba_sarpen_target_witels', function (Blueprint $table) {
            $table->foreign('sarpen_target_id')->references('id')->on('tr_ba_sarpen_targets')->onDelete('cascade');
            $table->string('sarpen_target_id');
            $table->integer('no');
            $table->primary(['sarpen_target_id', 'no']);
            $table->string('tsel_regional');
            $table->string('telkom_regional');
            $table->string('witel')->unique();
            $table->string('kota');
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
        Schema::dropIfExists('tr_ba_sarpen_target_witels');
    }
}

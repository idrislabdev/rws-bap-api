<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrBaSarpenTargets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_ba_sarpen_targets', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nama_project');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir')->nullable();
            $table->enum('status',['active', 'not_active']);
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
        Schema::dropIfExists('tr_ba_sarpen_targets');
    }
}

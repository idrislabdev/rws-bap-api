<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWitelToPenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ma_penggunas', function (Blueprint $table) {
            $table->string('witel_id')->nullable();
            $table->foreign('witel_id')->references('id')->on('ma_witels');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ma_penggunas', function (Blueprint $table) {
            //
        });
    }
}

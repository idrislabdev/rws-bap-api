<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldClientIdToMaPenggunasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ma_penggunas', function (Blueprint $table) {
            $table->string('klien_id', 100)->nullable();         
            $table->foreign('klien_id')->references('id')->on('ma_olo_kliens');            
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

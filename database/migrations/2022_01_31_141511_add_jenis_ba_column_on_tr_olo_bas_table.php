<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisBaColumnOnTrOloBasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_olo_bas', function (Blueprint $table) {
            $table->enum('jenis_ba', ['BAST', 'BAUT', 'BAST DAN BAUT']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_olo_bas', function (Blueprint $table) {
            //
        });
    }
}

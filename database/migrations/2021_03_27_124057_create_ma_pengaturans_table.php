<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaPengaturansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_pengaturans', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('nama', 100)->unique();
            $table->enum('tipe', ['TEXT', 'GAMBAR']);
            $table->string('nilai');
            $table->string('detail_nilai');
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
        Schema::dropIfExists('ma_pengaturans');
    }
}

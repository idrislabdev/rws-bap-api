<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaPenggunasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_penggunas', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('nama_lengkap', 150);
            $table->string('username', 35)->unique();
            $table->string('password', 100);
            $table->enum('role', ['ROOT','ADMIN', 'RWS', 'WITEL', 'MSO']);
            $table->enum('status', ['AKTIF', 'TIDAK-AKTIF']);
            $table->string('referrence_id')->unique()->nullable();
            $table->timestamps();
            $table->rememberToken();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ma_penggunas');
    }
}

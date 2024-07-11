<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaUserAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_user_accounts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nama', 100);
            $table->date('tanggal_lahir');
            $table->string('nik', 10);
            $table->enum('status_pegawai', ['ORGANIK', 'NON-ORGANIK']);
            $table->string('email', 100);
            $table->string('jabatan', 100);
            $table->string('unit', 100);
            $table->string('site_witel', 50);
            $table->string('datel', 50);
            $table->string('plaza', 50);
            $table->string('divisi', 100);
            $table->string('telegram_id', 100);
            $table->string('telegram_user', 100);
            $table->string('channel', 100);
            $table->string('nama_atasan', 100);
            $table->string('nik_atasan', 10);
            $table->string('jabatan_atasan', 100);
            $table->string('file_pakta_url');
            $table->string('image_ktp_url');
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('ma_user_accounts');
    }
}

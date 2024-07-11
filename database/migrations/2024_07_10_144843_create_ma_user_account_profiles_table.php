<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaUserAccountProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ma_user_account_profiles', function (Blueprint $table) {
            $table->string('user_account_id');
            $table->foreign('user_account_id')->references('id')->on('ma_user_accounts');
            $table->string('aplikasi');
            $table->primary(['user_account_id','aplikasi'], 'user_account_aplikasi_id');
            $table->json('profiles');
            $table->enum('status', ['TIDAK-AKTIF', 'AKTIF'])->nullable();
            $table->string('pengajuan_aplikasi_id');
            $table->foreign('pengajuan_aplikasi_id')->references('id')->on('tr_pengajuan_aplikasis');
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
        Schema::dropIfExists('ma_user_account_profiles');
    }
}
